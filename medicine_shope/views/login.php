<?php
// This view shows the login form for admin and customer users.
// The medicine shop logo is displayed on the login page.
$title = 'Login';
$error = $error ?? '';
$prefill = $prefill ?? '';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login</title><link rel="stylesheet" href="css/base.css"><link rel="stylesheet" href="css/auth.css"></head>
<body class="auth-page">
<div class="auth-wrapper">
    <div class="auth-banner"><img class="auth-logo" src="asset/medicineshopelogo.jpg" alt="Medicine Shop Logo"><h1>Online Medicine Shop</h1><p>Login to buy medicines, manage orders and control the shop system.</p></div>
    <div class="auth-card"><h2>Login</h2><p class="small-text">Enter your email and password</p>
        <?php if(!empty($error)): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST" action="index.php?page=login" onsubmit="return validateLogin()">
            <label>Email</label><input type="email" id="email" name="email" value="<?= htmlspecialchars($prefill) ?>" placeholder="Enter email">
            <label>Password</label><input type="password" id="password" name="password" placeholder="Enter password">
            <label class="check-line"><input type="checkbox" name="remember" <?= $prefill ? 'checked' : '' ?>> Remember me</label>
            <button class="btn full">Login</button>
        </form>
        <p class="auth-link">Do not have account? <a href="index.php?page=register">Register</a></p>
        <p class="demo-text"><b>Demo:</b> admin@shop.com / admin12345</p>
    </div>
</div>
<script>function validateLogin(){if(email.value.trim()===''){alert('Email required');return false;}if(password.value.trim()===''){alert('Password required');return false;}return true;}</script>
</body></html>
