<?php
// This admin view manages medicine records.
// Admin can add, update, delete and upload medicine images from this page.

$title      = 'Medicines';
$error      = $error ?? '';
$editing    = $editing ?? null;
$categories = $categories ?? [];
$medicines  = $medicines ?? [];

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Manage Medicines</h1>
    <p>Add medicine name, category, vendor, price, stock and image.</p>
</section>

<?php if ($error): ?>
    <div class="alert error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert success">
        Action completed: <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<section class="card form-card">
    <h3>
        <?= $editing ? 'Edit Medicine' : 'Add Medicine' ?>
    </h3>

    <form
        method="POST"
        enctype="multipart/form-data"
        action="index.php?page=medicines&action=<?= $editing ? 'update&id=' . htmlspecialchars($editing['id']) : 'add' ?>"
        onsubmit="return validateMedicine()"
    >
        <div class="form-grid">
            <div>
                <label for="mname">Name</label>

                <input
                    type="text"
                    id="mname"
                    name="name"
                    value="<?= htmlspecialchars($editing['name'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="category">Category</label>

                <select id="category" name="category_id">
                    <option value="">
                        Select Category
                    </option>

                    <?php foreach ($categories as $c): ?>
                        <option
                            value="<?= htmlspecialchars($c['id']) ?>"
                            <?= (($editing['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($c['name']) ?>
                            (<?= htmlspecialchars($c['category_type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="vendorName">Vendor</label>

                <input
                    type="text"
                    id="vendorName"
                    name="vendor_name"
                    value="<?= htmlspecialchars($editing['vendor_name'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="price">Price</label>

                <input
                    type="number"
                    id="price"
                    step="0.01"
                    name="price"
                    value="<?= htmlspecialchars($editing['price'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="stock">Stock</label>

                <input
                    type="number"
                    id="stock"
                    name="availability"
                    value="<?= htmlspecialchars($editing['availability'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="medicineImage">Medicine Image</label>

                <input
                    type="file"
                    id="medicineImage"
                    name="medicine_image"
                    accept="image/*"
                >
            </div>
        </div>

        <label for="description">Description</label>

        <textarea
            id="description"
            name="description"
        ><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>

        <button class="btn" type="submit">
            <?= $editing ? 'Update Medicine' : 'Add Medicine' ?>
        </button>

        <?php if ($editing): ?>
            <a class="btn light" href="index.php?page=medicines">
                Cancel
            </a>
        <?php endif; ?>
    </form>
</section>

<section class="card table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Vendor</th>
                <th>Unit Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($medicines as $i => $m): ?>
                <?php
                    $img = (!empty($m['image_path']) && file_exists($m['image_path']))
                        ? $m['image_path']
                        : 'asset/medicine-default.png';
                ?>

                <tr>
                    <td>
                        <?= $i + 1 ?>
                    </td>

                    <td>
                        <img
                            class="table-img"
                            src="<?= htmlspecialchars($img) ?>"
                            alt="Medicine Image"
                        >
                    </td>

                    <td>
                        <?= htmlspecialchars($m['name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($m['category_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($m['vendor_name']) ?>
                    </td>

                    <td>
                        Tk <?= number_format($m['price'], 2) ?>
                    </td>

                    <td>
                        <?= intval($m['availability']) ?>
                    </td>

                    <td>
                        <a
                            class="small-btn"
                            href="index.php?page=medicines&action=edit&id=<?= htmlspecialchars($m['id']) ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="small-btn danger"
                            href="index.php?page=medicines&action=delete&id=<?= htmlspecialchars($m['id']) ?>"
                            onclick="return confirm('Delete medicine?')"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script>
function validateMedicine() {
    let name = document.getElementById('mname').value.trim();
    let category = document.getElementById('category').value;
    let vendorName = document.getElementById('vendorName').value.trim();
    let price = document.getElementById('price').value;
    let stock = document.getElementById('stock').value;

    if (
        name === '' ||
        category === '' ||
        vendorName === '' ||
        price === '' ||
        stock === ''
    ) {
        alert('Please fill all medicine fields');
        return false;
    }

    if (parseFloat(price) <= 0) {
        alert('Price must be greater than 0');
        return false;
    }

    if (parseInt(stock) < 0) {
        alert('Stock cannot be negative');
        return false;
    }

    return true;
}
</script>

<?php require 'views/layout/footer.php'; ?>