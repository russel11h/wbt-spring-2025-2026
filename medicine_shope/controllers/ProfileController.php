<?php
// This controller loads and updates the logged-in user's profile.
// It saves profile details, password change, and uploaded profile picture.
// If no picture is uploaded, the Medicine Shop logo remains as the default photo.

function uploadProfilePicture($fieldName, $oldPicture = '')
{
    if (
        !isset($_FILES[$fieldName]) ||
        ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE
    ) {
        return [
            'ok'      => true,
            'path'    => $oldPicture ?: 'asset/Profile.png',
            'message' => ''
        ];
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return [
            'ok'      => false,
            'path'    => $oldPicture,
            'message' => 'Profile picture upload failed.'
        ];
    }

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    $originalName = $_FILES[$fieldName]['name'] ?? '';
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        return [
            'ok'      => false,
            'path'    => $oldPicture,
            'message' => 'Only JPG, JPEG, PNG or WEBP image is allowed.'
        ];
    }

    if (($_FILES[$fieldName]['size'] ?? 0) > 2 * 1024 * 1024) {
        return [
            'ok'      => false,
            'path'    => $oldPicture,
            'message' => 'Profile picture must be less than 2 MB.'
        ];
    }

    $uploadDir = 'uploads/profiles/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newName = 'profile_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    $newPath = $uploadDir . $newName;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $newPath)) {
        return [
            'ok'      => false,
            'path'    => $oldPicture,
            'message' => 'Could not save the uploaded profile picture.'
        ];
    }

    if (
        $oldPicture !== '' &&
        $oldPicture !== 'asset/Profile.png' &&
        file_exists($oldPicture)
    ) {
        unlink($oldPicture);
    }

    return [
        'ok'      => true,
        'path'    => $newPath,
        'message' => ''
    ];
}

function profileCtrl($conn)
{
    requireLogin();

    $error = '';
    $success = '';

    $user = findUserById($conn, $_SESSION['user']['id']);

    if (!$user) {
        session_destroy();

        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $current = $_POST['current_password'] ?? '';
        $newpass = $_POST['new_password'] ?? '';

        $oldPicture = $user['profile_picture'] ?: 'asset/Profile.png';

        if ($name === '' || $email === '' || $address === '' || $phone === '') {
            $error = 'Name, email, address and phone are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        } elseif (emailExists($conn, $email, $user['id'])) {
            $error = 'Email is used by another account.';
        } elseif (
            $newpass !== '' &&
            ($current === '' || !password_verify($current, $user['password_hash']))
        ) {
            $error = 'Current password is incorrect.';
        } elseif ($newpass !== '' && strlen($newpass) < 8) {
            $error = 'New password must be at least 8 characters.';
        } else {
            $upload = uploadProfilePicture('profile_picture', $oldPicture);

            if (!$upload['ok']) {
                $error = $upload['message'];
            } elseif (
                updateUserProfile(
                    $conn,
                    $user['id'],
                    $name,
                    $email,
                    $address,
                    $phone,
                    $upload['path']
                )
            ) {
                if ($newpass !== '') {
                    changeUserPassword($conn, $user['id'], $newpass);
                }

                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['profile_picture'] = $upload['path'];

                $success = ($newpass !== '')
                    ? 'Profile, picture and password updated successfully.'
                    : 'Profile updated successfully.';

                $user = findUserById($conn, $_SESSION['user']['id']);
            } else {
                $error = 'Profile update failed. Please try again.';
            }
        }
    }

    require 'views/profile.php';
}
?>