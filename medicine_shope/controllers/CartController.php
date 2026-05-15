<?php
// ================================================================
// CART + CHECKOUT CONTROLLER - Task 3
// ================================================================
function cartCtrl($conn) {
    requireCustomer();
    $items = getCartItems($conn, $_SESSION['user']['id']);
    $total = cartTotal($items);
    require 'views/customer/cart.php';
}

function cartAddAjax($conn) {
    requireCustomer();
    header('Content-Type: application/json');
    $medicineId = intval($_POST['medicine_id'] ?? 0);
    $qty = intval($_POST['quantity'] ?? 1);
    $medicine = getMedicine($conn, $medicineId);
    if (!$medicine || $qty <= 0 || $qty > intval($medicine['availability'])) {
        http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Invalid quantity or medicine stock.']); exit;
    }
    addToCart($conn, $_SESSION['user']['id'], $medicineId, $qty);
    echo json_encode(['ok'=>true,'count'=>cartCount($conn, $_SESSION['user']['id'])]); exit;
}

function cartUpdateAjax($conn) {
    requireCustomer();
    header('Content-Type: application/json');
    $cartId = intval($_POST['cart_id'] ?? 0);
    $qty = intval($_POST['quantity'] ?? 1);
    if ($cartId <= 0 || $qty <= 0) { http_response_code(422); echo json_encode(['ok'=>false]); exit; }
    updateCartQty($conn, $_SESSION['user']['id'], $cartId, $qty);
    echo json_encode(['ok'=>true]); exit;
}

function cartRemoveAjax($conn) {
    requireCustomer();
    header('Content-Type: application/json');
    removeCartItem($conn, $_SESSION['user']['id'], intval($_POST['cart_id'] ?? 0));
    echo json_encode(['ok'=>true]); exit;
}

function checkoutCtrl($conn) {
    requireCustomer();
    $items = getCartItems($conn, $_SESSION['user']['id']);
    if (empty($items)) { header('Location: index.php?page=cart'); exit; }
    $total = cartTotal($items);
    $user = findUserById($conn, $_SESSION['user']['id']);
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $address = trim($_POST['shipping_address'] ?? '');
        if ($address === '') $error = 'Shipping address is required.';
        else {
            $_SESSION['checkout_address'] = $address;
            header('Location: index.php?page=invoice'); exit;
        }
    }
    require 'views/customer/checkout.php';
}

function invoiceCtrl($conn) {
    requireCustomer();
    $items = getCartItems($conn, $_SESSION['user']['id']);
    if (empty($items)) { header('Location: index.php?page=cart'); exit; }
    $total = cartTotal($items);
    $address = $_SESSION['checkout_address'] ?? '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['payment_method'] ?? '';
        $methods = ['Credit Card','bKash','Nagad','Bank Transfer','Cash on Delivery'];
        if (!in_array($method, $methods)) $error = 'Please select a valid payment method.';
        else {
            mysqli_begin_transaction($conn);
            try {
                foreach ($items as $item) {
                    if ($item['quantity'] > $item['availability']) 
                        throw new Exception('Stock not available for ' . $item['name']);
                }
                $orderId = createOrder($conn, $_SESSION['user']['id'], $total, $address, $method);
                if (!$orderId) 
                    throw new Exception('Order creation failed.');
                foreach ($items as $item) {
                    addOrderItem($conn, $orderId, $item['id'], $item['quantity'], $item['price']);
                    reduceMedicineStock($conn, $item['id'], $item['quantity']);
                }
                addPayment($conn, $orderId, $total, $method);
                clearCart($conn, $_SESSION['user']['id']);
                unset($_SESSION['checkout_address']);
                mysqli_commit($conn);
                header('Location: index.php?page=my_orders&success=1');
                 exit;
            } 
            catch (Exception $e) 
            {
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }
        }
    }
    require 'views/customer/invoice.php';
}

function attachCustomerOrderDetails($conn, $orders) {
    foreach ($orders as $key => $order) {
        $orders[$key]['items'] = getOrderItems($conn, $order['id']);
        $orders[$key]['payment'] = getPaymentByOrder($conn, $order['id']) ? : ['payment_method'=>'', 'transaction_id'=>''];
    }
    return $orders;
}

function myOrdersCtrl($conn) {
    requireCustomer();
    $orders = attachCustomerOrderDetails($conn, getUserOrders($conn, $_SESSION['user']['id']));
    require 'views/customer/my_orders.php';
}
?>
