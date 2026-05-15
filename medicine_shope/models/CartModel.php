<?php
// ================================================================
// CART MODEL
// ================================================================
function getCartItems($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT c.id cart_id, c.quantity, m.*, cat.name category_name FROM cart c JOIN medicines m ON c.medicine_id=m.id JOIN categories cat ON m.category_id=cat.id WHERE c.user_id=? ORDER BY c.id DESC");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
function addToCart($conn, $userId, $medicineId, $qty) {
    $stmt = mysqli_prepare($conn, "INSERT INTO cart(user_id,medicine_id,quantity) VALUES(?,?,?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
    mysqli_stmt_bind_param($stmt, 'iii', $userId, $medicineId, $qty);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function updateCartQty($conn, $userId, $cartId, $qty) {
    $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity=? WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($stmt, 'iii', $qty, $cartId, $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function removeCartItem($conn, $userId, $cartId) {
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($stmt, 'ii', $cartId, $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function clearCart($conn, $userId) {
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function cartTotal($items) {
    $total = 0;
    foreach ($items as $item) $total += $item['price'] * $item['quantity'];
    return $total;
}
function cartCount($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(quantity),0) c FROM cart WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return intval($row['c'] ?? 0);
}
?>
