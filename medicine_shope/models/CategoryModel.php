<?php
// ================================================================
// CATEGORY MODEL
// ================================================================
function getCategories($conn) {
    $r = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_type, name");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}
function getCategory($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}
function addCategory($conn, $name, $type) {
    $stmt = mysqli_prepare($conn, "INSERT INTO categories(name, category_type) VALUES(?,?)");
    mysqli_stmt_bind_param($stmt, 'ss', $name, $type);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function updateCategory($conn, $id, $name, $type) {
    $stmt = mysqli_prepare($conn, "UPDATE categories SET name=?, category_type=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $type, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function categoryHasMedicines($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id FROM medicines WHERE category_id=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $has = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $has;
}
function deleteCategory($conn, $id) {
    if (categoryHasMedicines($conn, $id)) return false;
    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
function countCategories($conn) {
    $r = mysqli_query($conn, "SELECT COUNT(*) c FROM categories");
    return mysqli_fetch_assoc($r)['c'] ?? 0;
}
?>
