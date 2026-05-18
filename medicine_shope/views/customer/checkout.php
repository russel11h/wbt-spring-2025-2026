<?php
// This customer view confirms shipping address before invoice.
// Address is loaded from the profile and can be edited for this order.

$title = 'Checkout';
$error = $error ?? '';

$user = $user ?? [
    'address' => ''
];

$total = $total ?? 0;

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Checkout</h1>
    <p>Confirm your shipping address.</p>
</section>

<?php if ($error): ?>
    <div class="alert error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<section class="card checkout-card">
    <form method="POST" onsubmit="return validateAddress()">
        <label for="addr">Shipping Address</label>

        <textarea
            id="addr"
            name="shipping_address"
        ><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

        <h2>
            Total: Tk <?= number_format($total, 2) ?>
        </h2>

        <button class="btn" type="submit">
            Show Invoice
        </button>
    </form>
</section>

<script>
function validateAddress() {
    let address = document.getElementById('addr').value.trim();

    if (address === '') {
        alert('Shipping address is required');
        return false;
    }

    return true;
}
</script>

<?php require 'views/layout/footer.php'; ?>