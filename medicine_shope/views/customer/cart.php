<?php
// This customer view shows cart items.
// Customer can update quantity or remove items using AJAX.

$title = 'Cart';
$items = $items ?? [];
$total = $total ?? 0;

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>My Cart</h1>
    <p>Check medicine quantity and total price before checkout.</p>
</section>

<section class="card table-card">
    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Vendor</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($items as $it): ?>
                <?php
                    $lineTotal = $it['price'] * $it['quantity'];
                ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($it['name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($it['vendor_name']) ?>
                    </td>

                    <td>
                        Tk <?= number_format($it['price'], 2) ?>
                    </td>

                    <td>
                        <input
                            class="qty-box"
                            type="number"
                            min="1"
                            max="<?= intval($it['availability']) ?>"
                            value="<?= intval($it['quantity']) ?>"
                            onchange="updateQty(<?= intval($it['cart_id']) ?>, this.value)"
                        >
                    </td>

                    <td>
                        <b>
                            Tk <?= number_format($lineTotal, 2) ?>
                        </b>
                    </td>

                    <td>
                        <button class="small-btn danger"
                            type="button"
                            onclick="removeItem(<?= intval($it['cart_id']) ?>)"
                        >
                            Remove
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="6" class="empty">
                        Cart is empty.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<section class="cart-total card">
    <h2>
        Total: Tk <?= number_format($total, 2) ?>
    </h2>

    <?php if (!empty($items)): ?>
        <a class="btn" href="index.php?page=checkout">
            Proceed to Checkout
        </a>
    <?php endif; ?>
</section>

<script>
function updateQty(id, qty) {
    let fd = new FormData();

    fd.append('cart_id', id);
    fd.append('quantity', qty);

    fetch('index.php?page=ajax_cart_update', {
        method: 'POST',
        body: fd
    })
    .then(() => {
        location.reload();
    });
}

function removeItem(id) {
    let fd = new FormData();

    fd.append('cart_id', id);

    fetch('index.php?page=ajax_cart_remove', {
        method: 'POST',
        body: fd
    })
    .then(() => {
        location.reload();
    });
}
</script>

<?php require 'views/layout/footer.php'; ?>