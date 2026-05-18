<?php
// This view shows the registration form for new users.
// It checks password and confirm password before submitting.
// The registration image is displayed beside the form.

$title = 'Register';
$error = $error ?? '';

$old = $old ?? [
    'name'    => '',
    'email'   => '',
    'role'    => 'customer',
    'phone'   => '',
    'address' => ''
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?></title>

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body class="auth-page">
    <div class="auth-wrapper">

        <div class="auth-banner">
            <img
                class="register-image"
                src="asset/registration.png"
                alt="Registration Image"
            >

            <h1>Create Account</h1>

            <p>
                Register first, then you will directly go to the home page.
            </p>
        </div>

        <div class="auth-card">
            <h2>Registration</h2>

            <p class="small-text">
                Fill up every field correctly
            </p>

            <?php if ($error !== ''): ?>
                <div class="alert error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form
                method="POST"
                action="index.php?page=register"
                onsubmit="return validateRegister()"
            >
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                    placeholder="Enter full name"
                >

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    placeholder="Enter email"
                >

                <label for="role">Role</label>
                <select id="role" name="role">
                    <option
                        value="customer"
                        <?= (($old['role'] ?? 'customer') === 'customer') ? 'selected' : '' ?>
                    >
                        Customer
                    </option>

                    <option
                        value="admin"
                        <?= (($old['role'] ?? '') === 'admin') ? 'selected' : '' ?>
                    >
                        Admin
                    </option>
                </select>

                <label for="phone">Phone</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                    placeholder="Enter phone"
                >

                <label for="address">Address</label>
                <textarea
                    id="address"
                    name="address"
                    placeholder="Enter address"
                ><?= htmlspecialchars($old['address'] ?? '') ?></textarea>

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimum 8 characters"
                >

                <label for="confirm_password">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm password"
                >
                <p><br></p>

                <button class="btn full" type="submit">
                    Register
                </button>
            </form>

            <p class="auth-link">
                Already have account?
                <a href="index.php?page=login">Login</a>
            </p>
        </div>

    </div>

    <script>
        function valueOf(id) {
            return document.getElementById(id).value.trim();
        }

        function validateRegister() {
            let fields = [
                'name',
                'email',
                'phone',
                'address',
                'password',
                'confirm_password'
            ];

            for (let i = 0; i < fields.length; i++) {
                if (valueOf(fields[i]) === '') {
                    alert('Please fill all fields');
                    return false;
                }
            }

            let pass = document.getElementById('password').value;
            let confirmPass = document.getElementById('confirm_password').value;

            if (pass.length < 8) {
                alert('Password must be at least 8 characters');
                return false;
            }

            if (pass !== confirmPass) {
                alert('Password and confirm password do not match');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>