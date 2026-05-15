<?php
// ================================================================
// ORDER MODEL
// ================================================================
function createOrder($conn, $userId, $total, $address, $paymentMethod) {
    $stmt = mysqli_prepare($conn, "INSERT INTO orders(user_id,total_amount,shipping_address,payment_method,status) VALUES(?,?,?,?,'pending')");
    mysqli_stmt_bind_param($stmt, 'idss', $userId, $total, $address, $paymentMethod);
    $ok = mysqli_stmt_execute($stmt);
    $orderId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $ok ? $orderId : false;
}
function addOrderItem($conn, $orderId, $medicineId, $qty, $price) {
    $stmt = mysqli_prepare($conn, "INSERT INTO order_items(order_id,medicine_id,quantity,unit_price) VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'iiid', $orderId, $medicineId, $qty, $price);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function addPayment($conn, $orderId, $amount, $method) {
    $transaction = 'TXN-' . time() . '-' . rand(1000,9999);
    $stmt = mysqli_prepare($conn, "INSERT INTO payments(order_id,amount,payment_method,transaction_id) VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'idss', $orderId, $amount, $method, $transaction);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function getOrders($conn) {
    $sql = "SELECT o.*, u.name customer_name, u.email, u.phone FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.id DESC";
    $r = mysqli_query($conn, $sql);
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}
function getAcceptedOrders($conn) {
    $sql = "SELECT o.*, u.name customer_name, u.email, u.phone FROM orders o JOIN users u ON o.user_id=u.id WHERE o.status='accepted' ORDER BY o.id DESC";
    $r = mysqli_query($conn, $sql);
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}
function getUserOrders($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function getPaymentByOrder($conn, $orderId) {
    $stmt = mysqli_prepare($conn, "SELECT amount, payment_method, transaction_id, payment_date FROM payments WHERE order_id=? ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ?: ['amount'=>0, 'payment_method'=>'', 'transaction_id'=>'N/A', 'payment_date'=>''];
}

function getOrderItems($conn, $orderId) {
    $stmt = mysqli_prepare($conn, "SELECT oi.*, m.name medicine_name, m.vendor_name FROM order_items oi JOIN medicines m ON oi.medicine_id=m.id WHERE oi.order_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
function updateOrderStatus($conn, $orderId, $status) {
    $stmt = mysqli_prepare($conn, "UPDATE orders SET status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $orderId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function countPendingOrders($conn) {
    $r = mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status='pending'");
    return mysqli_fetch_assoc($r)['c'] ?? 0;
}
?>
