<?php
// This admin view adds, updates and deletes medicine categories.
// Category image can be uploaded while adding or editing a category.
// Categories are divided into liquid and solid types.

$title      = 'Categories';
$error      = $error ?? '';
$editing    = $editing ?? null;
$categories = $categories ?? [];

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Manage Categories</h1>
    <p>Add or edit medicine categories with image, name and type.</p>
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
        <?= $editing ? 'Edit Category' : 'Add Category' ?>
    </h3>

    <form
        method="POST"
        enctype="multipart/form-data"
        action="index.php?page=categories&action=<?= $editing ? 'update&id=' . htmlspecialchars($editing['id']) : 'add' ?>"
        onsubmit="return validateCategory()"
    >
        <div class="form-grid two">
            <div>
                <label for="catName">Category Name</label>

                <input
                    type="text"
                    id="catName"
                    name="name"
                    value="<?= htmlspecialchars($editing['name'] ?? '') ?>"
                >
            </div>

            <div>
                <label for="catType">Type</label>

                <select id="catType" name="category_type">
                    <option value="">
                        Select
                    </option>

                    <option
                        value="solid"
                        <?= (($editing['category_type'] ?? '') === 'solid') ? 'selected' : '' ?>
                    >
                        Solid
                    </option>

                    <option
                        value="liquid"
                        <?= (($editing['category_type'] ?? '') === 'liquid') ? 'selected' : '' ?>
                    >
                        Liquid
                    </option>
                </select>
            </div>

            <div>
                <label for="categoryImage">Category Image</label>

                <input
                    type="file"
                    id="categoryImage"
                    name="category_image"
                    accept="image/*"
                >
            </div>

            <?php if ($editing): ?>
                <?php
                    $preview = (!empty($editing['image_path']) && file_exists($editing['image_path']))
                        ? $editing['image_path']
                        : 'asset/medicine-default.png';
                ?>

                <div>
                    <label>Current Image</label>

                    <img
                        class="table-img"
                        src="<?= htmlspecialchars($preview) ?>"
                        alt="Category Image"
                    >
                </div>
            <?php endif; ?>
        </div>

        <button class="btn" type="submit">
            <?= $editing ? 'Update Category' : 'Add Category' ?>
        </button>

        <?php if ($editing): ?>
            <a class="btn light" href="index.php?page=categories">
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
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($categories as $i => $cat): ?>
                <?php
                    $img = (!empty($cat['image_path']) && file_exists($cat['image_path']))
                        ? $cat['image_path']
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
                            alt="Category Image"
                        >
                    </td>

                    <td>
                        <?= htmlspecialchars($cat['name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($cat['category_type']) ?>
                    </td>

                    <td>
                        <a
                            class="small-btn"
                            href="index.php?page=categories&action=edit&id=<?= htmlspecialchars($cat['id']) ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="small-btn danger"
                            href="index.php?page=categories&action=delete&id=<?= htmlspecialchars($cat['id']) ?>"
                            onclick="return confirm('Delete category?')"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="5" class="empty">
                        No category found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<script>
function validateCategory() {
    let categoryName = document.getElementById('catName').value.trim();
    let categoryType = document.getElementById('catType').value;
    let image = document.getElementById('categoryImage').files[0];

    if (categoryName === '' || categoryType === '') {
        alert('Category name and type required');
        return false;
    }

    if (image && image.size > 2 * 1024 * 1024) {
        alert('Category image must be less than 2 MB');
        return false;
    }

    return true;
}
</script>

<?php require 'views/layout/footer.php'; ?>
