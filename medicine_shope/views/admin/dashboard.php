<?php
// This admin view shows the main dashboard summary.
// It displays total medicines, categories, customers and pending orders.
$title = 'Admin Dashboard';
$stats = $stats ?? ['medicines'=>0,'categories'=>0,'customers'=>0,'pending'=>0];
require 'views/layout/header.php';
?>
<section class="page-title-box">
    <h1>Admin Dashboard</h1>
    <p>Control all medicine shop management tasks from here.</p>
</section>

<section class="stats-grid">
    <div class="stat-card"><span>Medicines</span><b><?= $stats['medicines'] ?></b></div>
    <div class="stat-card"><span>Categories</span><b><?= $stats['categories'] ?></b></div>
    <div class="stat-card"><span>Customers</span><b><?= $stats['customers'] ?></b></div>
    <div class="stat-card"><span>Pending Orders</span><b><?= $stats['pending'] ?></b></div>
</section>

<section class="card admin-links">
    <a href="index.php?page=categories">Manage Categories</a>
    <a href="index.php?page=medicines">Manage Medicines</a>
    <a href="index.php?page=customers">Manage Customers</a>
    <a href="index.php?page=orders">Purchase Requests</a>
    <a href="index.php?page=history">Order History</a>
</section>
<?php require 'views/layout/footer.php'; ?>
