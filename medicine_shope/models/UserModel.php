<?php
// ================================================================
// USER MODEL - users table functions
// ================================================================
function clean($value) {
    return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function findUserByEmail($conn, $email) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function findUserById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function emailExists($conn, $email, $excludeId = null) {
    if ($excludeId) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, 'si', $email, $excludeId);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

function createUser($conn, $name, $email, $password, $role, $address, $phone) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO users(name,email,password_hash,role,address,phone,profile_picture) VALUES(?,?,?,?,?,?,?)");
    $defaultPicture = 'asset/medicineshopelogo.jpg';
    mysqli_stmt_bind_param($stmt, 'sssssss', $name, $email, $hash, $role, $address, $phone, $defaultPicture);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function updateUserProfile($conn, $id, $name, $email, $address, $phone, $picture = null) {
    if ($picture) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name=?, email=?, address=?, phone=?, profile_picture=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sssssi', $name, $email, $address, $phone, $picture, $id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name=?, email=?, address=?, phone=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'ssssi', $name, $email, $address, $phone, $id);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function changeUserPassword($conn, $id, $newPassword) {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "UPDATE users SET password_hash=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $hash, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function saveRememberToken($conn, $id, $token) {
    $hash = password_hash($token, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $hash, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function clearRememberToken($conn, $id) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token=NULL WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function getAllCustomers($conn) {
    $r = mysqli_query($conn, "SELECT id,name,email,address,phone,created_at FROM users WHERE role='customer' ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function deleteCustomer($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id=? AND role='customer'");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function countCustomers($conn) {
    $r = mysqli_query($conn, "SELECT COUNT(*) c FROM users WHERE role='customer'");
    return mysqli_fetch_assoc($r)['c'] ?? 0;
}
?>
