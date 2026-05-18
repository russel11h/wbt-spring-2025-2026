<?php
// This view shows medicine items on the home page.
// The only search box is in the top header and it searches by medicine name.
// AJAX updates the medicine list without refreshing the page.

$title = 'Home';

$categories = $categories ?? [];
$medicines   = $medicines ?? [];

$q = $_GET['q'] ?? ($_SESSION['last_search'] ?? ($_COOKIE['last_search'] ?? ''));

$categoryId = intval($_GET['category'] ?? 0);

require 'views/layout/header.php';
?>

<section class="hero-card">
    <div>
        <h1>Online Medicine Shop</h1>

        <p>
            Search medicine by name, select category from left side,
            and add items to cart.
        </p>
    </div>

    <img
        src="asset/medicineshopelogo.jpg"
        alt="Medicine Shop Logo"
    >
</section>

<section class="medicine-grid" id="medicineGrid">
    <?php if (empty($medicines)): ?>
        <div class="card empty">
            No medicine found.
        </div>
    <?php endif; ?>

    <?php foreach ($medicines as $m): ?>
        <?php
            $img = (!empty($m['image_path']) && file_exists($m['image_path']))
                ? $m['image_path']
                : 'asset/medicine-default.png';
        ?>

        <div class="medicine-card">
            <div class="medicine-image">
                <img
                    src="<?= htmlspecialchars($img) ?>"
                    alt="Medicine"
                >
            </div>

            <h3><?= htmlspecialchars($m['name']) ?></h3>

            <p>
                <?= htmlspecialchars($m['category_name']) ?>
                |
                <?= htmlspecialchars($m['category_type']) ?>
            </p>

            <p>
                <b>Vendor:</b>
                <?= htmlspecialchars($m['vendor_name']) ?>
            </p>

            <p>
                <b>Price:</b>
                Tk <?= number_format($m['price'], 2) ?>
                |
                <b>Stock:</b>
                <?= intval($m['availability']) ?>
            </p>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'customer'): ?>
                <div class="cart-row">
                    <input
                        type="number"
                        id="qty<?= htmlspecialchars($m['id']) ?>"
                        value="1"
                        min="1"
                        max="<?= intval($m['availability']) ?>"
                    >

                    <button
                        class="btn"
                        type="button"
                        onclick="addCart(<?= htmlspecialchars($m['id']) ?>)"
                    >
                        Add to Cart
                    </button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>

<script>
let currentCategory = <?= (int) $categoryId ?>;

function escapeHtml(s) {
    return String(s).replace(/[&<>'"]/g, function (c) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#039;',
            '"': '&quot;'
        }[c];
    });
}

function renderMedicine(list) {
    let grid = document.getElementById('medicineGrid');

    grid.innerHTML = '';

    if (list.length === 0) {
        grid.innerHTML = '<div class="card empty">No medicine found.</div>';
        return;
    }

    let customer = <?= (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'customer') ? 'true' : 'false' ?>;

    list.forEach(function (m) {
        let image = m.image_path && m.image_path.length > 0
            ? m.image_path
            : 'asset/medicine-default.png';

        let cartHtml = customer
            ? `
                <div class="cart-row">
                    <input
                        type="number"
                        id="qty${m.id}"
                        value="1"
                        min="1"
                        max="${m.availability}"
                    >

                    <button
                        class="btn"
                        type="button"
                        onclick="addCart(${m.id})"
                    >
                        Add to Cart
                    </button>
                </div>
            `
            : '';

        grid.innerHTML += `
            <div class="medicine-card">
                <div class="medicine-image">
                    <img
                        src="${escapeHtml(image)}"
                        alt="Medicine"
                    >
                </div>

                <h3>${escapeHtml(m.name)}</h3>

                <p>
                    ${escapeHtml(m.category_name)}
                    |
                    ${escapeHtml(m.category_type)}
                </p>

                <p>
                    <b>Vendor:</b>
                    ${escapeHtml(m.vendor_name)}
                </p>

                <p>
                    <b>Price:</b>
                    Tk ${parseFloat(m.price).toFixed(2)}
                    |
                    <b>Stock:</b>
                    ${parseInt(m.availability)}
                </p>

                ${cartHtml}
            </div>
        `;
    });
}

function searchNow() {
    let q = document.getElementById('topSearchInput').value;

    let url = 'index.php?page=ajax_medicine_search'
        + '&q=' + encodeURIComponent(q)
        + '&category=' + currentCategory;

    fetch(url)
        .then(response => response.json())
        .then(data => renderMedicine(data));
}

let form = document.getElementById('topSearchForm');

if (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (document.getElementById('medicineGrid')) {
            searchNow();
        } else {
            window.location = 'index.php?page=home&q='
                + encodeURIComponent(document.getElementById('topSearchInput').value);
        }
    });
}

let topInput = document.getElementById('topSearchInput');

if (topInput) {
    topInput.addEventListener('keyup', function () {
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(searchNow, 300);
    });
}

function addCart(id) {
    let qty = document.getElementById('qty' + id).value;

    let fd = new FormData();

    fd.append('medicine_id', id);
    fd.append('quantity', qty);

    fetch('index.php?page=ajax_cart_add', {
        method: 'POST',
        body: fd
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            document.getElementById('cartCount').innerText = data.count;
            alert('Medicine added to cart');
        } else {
            alert(data.message || 'Could not add to cart');
        }
    });
}
</script>

<?php require 'views/layout/footer.php'; ?>