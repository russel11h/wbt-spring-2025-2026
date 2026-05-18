<?php
// This layout file shows the common top area for customer and admin pages.
// It contains only one search box at the top and shows cart/profile/logout.
// The left side category panel is designed like a simple list box.
$currentPage = $_GET['page'] ?? 'home';
$authUser = $_SESSION['user'] ?? null;
$profilePic = 'asset/Profile.png';
$cartNo = 0;
$layoutCategories = [];
if (isset($conn)) {
    $layoutCategories = getCategories($conn);
    if ($authUser) {
        $dbUser = findUserById($conn, $authUser['id']);
        if ($dbUser && !empty($dbUser['profile_picture']) && file_exists($dbUser['profile_picture'])) {
            $profilePic = $dbUser['profile_picture'];
        }
        if (($authUser['role'] ?? '') === 'customer') {
            $cartNo = cartCount($conn, $authUser['id']);
        }
    }
}
function activeMenu($name,$current){ return $name===$current ? 'active' : ''; }
$searchText = $_GET['q'] ?? ($_SESSION['last_search'] ?? ($_COOKIE['last_search'] ?? ''));
$searchText = htmlspecialchars($searchText);
$selectedCategory = intval($_GET['category'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'Medicine Shop') ?></title>
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/layout.css">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/customer.css">
<link rel="stylesheet" href="css/profile.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php?page=home">
        <img src="asset/medicineshopelogo.jpg" alt="Company Logo">
        <span>Medicine Shop</span>
    </a>

    <form class="top-search" id="topSearchForm" method="GET" action="index.php">
        <input type="hidden" name="page" value="home">
        <input type="hidden" name="category" id="topCategory" value="<?= (int)$selectedCategory ?>">
        <input id="topSearchInput" name="q" value="<?= $searchText ?>" placeholder="Search for medicine name...">
        <button type="submit" class="search-btn"><img src="asset/Search.png" alt="Search"></button>
    </form>

    <nav class="top-menu">
        <?php if($authUser && ($authUser['role'] ?? '')==='admin'): ?>
            <a class="<?= activeMenu('admin',$currentPage) ?>" href="index.php?page=admin">Dashboard</a>
            <a class="<?= activeMenu('categories',$currentPage) ?>" href="index.php?page=categories">Categories</a>
            <a class="<?= activeMenu('medicines',$currentPage) ?>" href="index.php?page=medicines">Medicines</a>
            <a class="<?= activeMenu('customers',$currentPage) ?>" href="index.php?page=customers">Customers</a>
            <a class="<?= activeMenu('orders',$currentPage) ?>" href="index.php?page=orders">Orders</a>
            <a class="<?= activeMenu('history',$currentPage) ?>" href="index.php?page=history">History</a>
        <?php elseif($authUser && ($authUser['role'] ?? '')==='customer'): ?>
            <a class="<?= activeMenu('my_orders',$currentPage) ?>" href="index.php?page=my_orders">My Orders</a>
        <?php endif; ?>
    </nav>

    <div class="nav-account">
        <?php if($authUser && ($authUser['role'] ?? '')==='customer'): ?>
            <a class="cart-top <?= activeMenu('cart',$currentPage) ?>" href="index.php?page=cart" title="Go to cart">
                <img src="asset/ShoppingCart.png" alt="Cart"><span id="cartCount"><?= (int)$cartNo ?></span>
            </a>
        <?php endif; ?>
        <?php if($authUser): ?>
            <a class="profile-link <?= activeMenu('profile',$currentPage) ?>" href="index.php?page=profile">
                <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile"><span><?= htmlspecialchars($authUser['name'] ?? 'User') ?></span>
            </a>
            <a class="logout-btn" href="index.php?page=logout">Logout</a>
        <?php else: ?>
            <a class="login-btn <?= activeMenu('login',$currentPage) ?>" href="index.php?page=login">Login</a>
            <a class="register-btn <?= activeMenu('register',$currentPage) ?>" href="index.php?page=register">Register</a>
        <?php endif; ?>
    </div>
</header>
<div class="page-body">
    <aside class="left-category">
        <div class="category-box">
            <a class="category-row <?= $selectedCategory===0?'active':'' ?>" href="index.php?page=home&q=<?= urlencode($_GET['q'] ?? '') ?>">
                <span class="cat-img"><img src="asset/medicineshopelogo.jpg" alt="All"></span>
                <span>All Categories</span>
                <b>›</b>
            </a>
            <?php foreach($layoutCategories as $cat): ?>
                <?php
                    $catImage = (!empty($cat['image_path']) && file_exists($cat['image_path']))
                        ? $cat['image_path']
                        : 'asset/medicine-default.png';
                ?>
                <a class="category-row <?= $selectedCategory==intval($cat['id'])?'active':'' ?>" href="index.php?page=home&category=<?= $cat['id'] ?>&q=<?= urlencode($_GET['q'] ?? '') ?>">
                    <span class="cat-img"><img src="<?= htmlspecialchars($catImage) ?>" alt="Category"></span>
                    <span><?= htmlspecialchars($cat['name']) ?></span>
                    <b>›</b>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>
    <main class="container">
