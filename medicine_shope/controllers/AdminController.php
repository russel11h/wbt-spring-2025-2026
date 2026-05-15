<?php
// ================================================================
// ADMIN CONTROLLER - Task 2
// ================================================================
function adminDashboardCtrl($conn) {
    requireAdmin();
    $stats = [
        'medicines' => countMedicines($conn),
        'categories' => countCategories($conn),
        'customers' => countCustomers($conn),
        'pending' => countPendingOrders($conn)
    ];
    require 'views/admin/dashboard.php';
}

function categoryCtrl($conn) {
    requireAdmin();
    $error = '';
    $editing = null;
    $action = $_GET['action'] ?? 'list';

    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $type = $_POST['category_type'] ?? '';
        if ($name === '' || !in_array($type, ['liquid','solid'])) 
            {
                $error = 'Valid category name and type are required.';
        }
        else { addCategory($conn, $name, $type); header('Location: index.php?page=categories&msg=added'); exit; }
    }
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_GET['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $type = $_POST['category_type'] ?? '';
        if ($name === '' || !in_array($type, ['liquid','solid'])) { $error = 'Valid category name and type are required.'; $editing = ['id'=>$id,'name'=>$name,'category_type'=>$type]; }
        else { updateCategory($conn, $id, $name, $type); header('Location: index.php?page=categories&msg=updated'); exit; }
    }
    if ($action === 'edit') $editing = getCategory($conn, intval($_GET['id'] ?? 0));
    if ($action === 'delete') {
        $ok = deleteCategory($conn, intval($_GET['id'] ?? 0));
        header('Location: index.php?page=categories&msg=' . ($ok ? 'deleted' : 'blocked')); exit;
    }
    $categories = getCategories($conn);
    require 'views/admin/categories.php';
}

function uploadMedicineImage($fieldName, $oldImage = '') {
    if (!isset($_FILES[$fieldName]) || ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok'=>true, 'path'=>$oldImage ?: 'asset/medicine-default.png', 'message'=>''];
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return ['ok'=>false, 'path'=>$oldImage, 'message'=>'Medicine image upload failed.'];
    }

    $allowedExt = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($_FILES[$fieldName]['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        return ['ok'=>false, 'path'=>$oldImage, 'message'=>'Only JPG, JPEG, PNG or WEBP image is allowed.'];
    }

    if (($_FILES[$fieldName]['size'] ?? 0) > 2 * 1024 * 1024) {
        return ['ok'=>false, 'path'=>$oldImage, 'message'=>'Medicine image must be less than 2 MB.'];
    }

    $uploadDir = 'uploads/medicines/';
    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

    $newName = 'medicine_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $newPath = $uploadDir . $newName;
    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $newPath)) {
        return ['ok'=>false, 'path'=>$oldImage, 'message'=>'Could not save medicine image.'];
    }

    if ($oldImage !== '' && $oldImage !== 'asset/medicine-default.png' && file_exists($oldImage)) {
        unlink($oldImage);
    }
    return ['ok'=>true, 'path'=>$newPath, 'message'=>''];
}

function medicineCtrl($conn) {
    requireAdmin();
    $error = '';
    $editing = null;
    $action = $_GET['action'] ?? 'list';

    if (($action === 'add' || $action === 'update') && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_GET['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $categoryId = intval($_POST['category_id'] ?? 0);
        $vendor = trim($_POST['vendor_name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $stock = trim($_POST['availability'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $oldMedicine = ($action === 'update') ? getMedicine($conn, $id) : null;
        $oldImage = $oldMedicine['image_path'] ?? 'asset/medicine-default.png';

        if ($name === '' || $vendor === '' || $categoryId <= 0 || $price === '' || $stock === '') {
            $error = 'All medicine fields are required.';
        } elseif (!is_numeric($price) || floatval($price) <= 0) {
            $error = 'Price must be greater than 0.';
        } elseif (!ctype_digit($stock) || intval($stock) < 0) {
            $error = 'Availability must be a non-negative whole number.';
        } else {
            $upload = uploadMedicineImage('medicine_image', $oldImage);
            if (!$upload['ok']) {
                $error = $upload['message'];
            } elseif ($action === 'add') {
                addMedicine($conn, $name, $categoryId, $vendor, floatval($price), intval($stock), $description, $upload['path']);
                header('Location: index.php?page=medicines&msg=added'); exit;
            } else {
                updateMedicine($conn, $id, $name, $categoryId, $vendor, floatval($price), intval($stock), $description, $upload['path']);
                header('Location: index.php?page=medicines&msg=updated'); exit;
            }
        }
        $editing = ['id'=>$id,'name'=>$name,'category_id'=>$categoryId,'vendor_name'=>$vendor,'price'=>$price,'availability'=>$stock,'description'=>$description,'image_path'=>$oldImage];
    }
    if ($action === 'edit') $editing = getMedicine($conn, intval($_GET['id'] ?? 0));
    if ($action === 'delete') {
        $ok = deleteMedicine($conn, intval($_GET['id'] ?? 0));
        header('Location: index.php?page=medicines&msg=' . ($ok ? 'deleted' : 'blocked')); exit;
    }
    $categories = getCategories($conn);
    $medicines = getMedicines($conn);
    require 'views/admin/medicines.php';
}

function customersCtrl($conn) {
    requireAdmin();
    if (($_GET['action'] ?? '') === 'delete') {
        deleteCustomer($conn, intval($_GET['id'] ?? 0));
        header('Location: index.php?page=customers&msg=deleted'); exit;
    }
    $customers = getAllCustomers($conn);
    require 'views/admin/customers.php';
}


function attachOrderDetails($conn, $orders) {
    foreach ($orders as $key => $order) {
        $orders[$key]['items'] = getOrderItems($conn, $order['id']);
        $orders[$key]['payment'] = getPaymentByOrder($conn, $order['id']) ?: ['payment_method'=>'', 'transaction_id'=>''];
    }
    return $orders;
}

function ordersCtrl($conn) {
    requireAdmin();
    $orders = attachOrderDetails($conn, getOrders($conn));
    require 'views/admin/orders.php';
}

function orderStatusAjax($conn) {
    requireAdmin();
    header('Content-Type: application/json');
    $id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($id <= 0 || !in_array($status, ['accepted','rejected'])) {
        http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Invalid order request']); exit;
    }
    $ok = updateOrderStatus($conn, $id, $status);
    echo json_encode(['ok'=>$ok, 'status'=>$status]); exit;
}

function historyCtrl($conn) {
    requireAdmin();
    $orders = attachOrderDetails($conn, getAcceptedOrders($conn));
    require 'views/admin/history.php';
}
?>
