<?php
// This admin view adds, updates and deletes medicine categories.
// Categories are divided into liquid and solid types.
$title = 'Categories';
$error = $error ?? '';
$editing = $editing ?? null;
$categories = $categories ?? [];
require 'views/layout/header.php';
?>
<section class="page-title-box"><h1>Manage Categories</h1><p>Add or edit medicine genres/categories.</p></section>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if (isset($_GET['msg'])): ?><div class="alert success">Action completed: <?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>

<section class="card form-card">
    <h3><?= $editing ? 'Edit Category' : 'Add Category' ?></h3>
    <form method="POST" action="index.php?page=categories&action=<?= $editing ? 'update&id='.$editing['id'] : 'add' ?>" onsubmit="return validateCategory()">
        <div class="form-grid two">
            <div><label>Category Name</label><input id="catName" name="name" value="<?= htmlspecialchars($editing['name'] ?? '') ?>"></div>
            <div><label>Type</label><select name="category_type" id="catType"><option value="">Select</option><option value="solid" <?= (($editing['category_type'] ?? '')==='solid')?'selected':'' ?>>Solid</option><option value="liquid" <?= (($editing['category_type'] ?? '')==='liquid')?'selected':'' ?>>Liquid</option></select></div>
        </div>
        <button class="btn"><?= $editing ? 'Update Category' : 'Add Category' ?></button>
        <?php if ($editing): ?><a class="btn light" href="index.php?page=categories">Cancel</a><?php endif; ?>
    </form>
</section>

<section class="card table-card">
<table>
    <thead><tr><th>No</th><th>Name</th><th>Type</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($categories as $i=>$cat): ?>
        <tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($cat['name']) ?></td><td><?= htmlspecialchars($cat['category_type']) ?></td><td><a class="small-btn" href="index.php?page=categories&action=edit&id=<?= $cat['id'] ?>">Edit</a><a class="small-btn danger" onclick="return confirm('Delete category?')" href="index.php?page=categories&action=delete&id=<?= $cat['id'] ?>">Delete</a></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>
</section>
<script>
function validateCategory(){if(catName.value.trim()==='' || catType.value===''){alert('Category name and type required');return false;}return true;}
</script>
<?php require 'views/layout/footer.php'; ?>
