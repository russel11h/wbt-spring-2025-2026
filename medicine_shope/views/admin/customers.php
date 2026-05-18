<?php
// This admin view shows all registered customers.
// Admin can view phone, address, profile image and delete customers.

$title     = 'Customers';
$customers = $customers ?? [];

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Customers</h1>
    <p>All customer profile information is shown here.</p>
</section>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert success">
        Customer <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<section class="card table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($customers as $i => $c): ?>
                <?php
                    $img = !empty($c['profile_picture'])
                        ? $c['profile_picture']
                        : 'asset/medicineshopelogo.jpg';

                    if (!file_exists($img)) {
                        $img = 'asset/medicineshopelogo.jpg';
                    }
                ?>

                <tr>
                    <td>
                        <?= $i + 1 ?>
                    </td>

                    <td>
                        <img
                            class="table-img"
                            src="<?= htmlspecialchars($img) ?>"
                            alt="Customer Photo"
                        >
                    </td>

                    <td>
                        <?= htmlspecialchars($c['name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($c['email']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($c['phone']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($c['address']) ?>
                    </td>

                    <td>
                        <a
                            class="small-btn danger"
                            href="index.php?page=customers&action=delete&id=<?= htmlspecialchars($c['id']) ?>"
                            onclick="return confirm('Delete customer?')"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="7" class="empty">
                        No customer found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require 'views/layout/footer.php'; ?>