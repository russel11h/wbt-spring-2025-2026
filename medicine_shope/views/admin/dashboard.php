<?php
// This admin view shows the main dashboard summary.
// It displays total medicines, categories, customers and pending orders.

$title = 'Admin Dashboard';

$stats = $stats ?? [
    'medicines'  => 0,
    'categories' => 0,
    'customers'  => 0,
    'pending'    => 0
];

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Admin Dashboard</h1>
    <p>Control all medicine shop management tasks from here.</p>
</section>

<section class="stats-grid">
    <div class="stat-card">
        <span>Medicines</span>
        <b><?= intval($stats['medicines']) ?></b>
    </div>

    <div class="stat-card">
        <span>Categories</span>
        <b><?= intval($stats['categories']) ?></b>
    </div>

    <div class="stat-card">
        <span>Customers</span>
        <b><?= intval($stats['customers']) ?></b>
    </div>

    <div class="stat-card">
        <span>Pending Orders</span>
        <b><?= intval($stats['pending']) ?></b>
    </div>
</section>


<?php require 'views/layout/footer.php'; ?>