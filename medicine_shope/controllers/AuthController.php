<?php
// ================================================================
// AUTH CONTROLLER - Task 1
// ================================================================

function loginCtrl($conn)
{
    $error = '';
    $prefill = $_COOKIE['remember_email'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if ($email === '' || $password === '') {
            $error = 'Email and password are required.';
        } else {
            $user = findUserByEmail($conn, $email);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];

                if ($remember) {
                    setcookie('remember_email', $email, time() + 86400 * 30, '/');
                } else {
                    setcookie('remember_email', '', time() - 3600, '/');
                }

                header('Location: index.php?page=home');
                exit;
            }

            $error = 'Invalid email or password.';
        }
    }

    require 'views/login.php';
}

function registerCtrl($conn)
{
    $error = '';
    $success = '';

    $old = [
        'name'    => '',
        'email'   => '',
        'role'    => 'customer',
        'address' => '',
        'phone'   => ''
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'customer';
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $old = compact(
            'name',
            'email',
            'role',
            'address',
            'phone'
        );

        if (
            $name === '' ||
            $email === '' ||
            $password === '' ||
            $address === '' ||
            $phone === ''
        ) {
            $error = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (!in_array($role, ['admin', 'customer'])) {
            $error = 'Invalid role selected.';
        } elseif (emailExists($conn, $email)) {
            $error = 'Email already exists.';
        } else {
            if (createUser($conn, $name, $email, $password, $role, $address, $phone)) {
                $user = findUserByEmail($conn, $email);

                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];

                header('Location: index.php?page=home');
                exit;
            } else {
                $error = 'Registration failed.';
            }
        }
    }

    require 'views/register.php';
}

function logoutCtrl($conn)
{
    if (isset($_SESSION['user'])) {
        clearRememberToken($conn, $_SESSION['user']['id']);
    }

    $_SESSION = [];

    session_destroy();

    setcookie('remember_email', '', time() - 3600, '/');

    header('Location: index.php?page=login');
    exit;
}
?>