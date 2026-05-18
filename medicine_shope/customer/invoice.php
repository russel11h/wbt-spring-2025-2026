<?php
// This customer view shows invoice and payment method options.
// Payment radio buttons are aligned and highlighted when selected.

$title   = 'Invoice';
$error   = $error ?? '';
$items   = $items ?? [];
$total   = $total ?? 0;
$address = $address ?? '';

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Invoice & Payment</h1>
    <p>Review order and select one payment method.</p>
</section>

<?php if ($error): ?>
    <div class="alert error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<section class="card invoice-card">
    <p>
        <b>Shipping Address:</b>
        <?= htmlspecialchars($address) ?>
    </p>

    <div class="table-card no-padding">
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
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
                            <?= intval($it['quantity']) ?>
                        </td>

                        <td>
                            Tk <?= number_format($it['price'], 2) ?>
                        </td>

                        <td>
                            <b>
                                Tk <?= number_format($lineTotal, 2) ?>
                            </b>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="invoice-total">
        Grand Total: Tk <?= number_format($total, 2) ?>
    </h2>

    <form method="POST" onsubmit="return validatePaymentMethod()">
        <label>Payment Method</label>

        <div class="payment-methods">
            <?php foreach (['Credit Card', 'bKash', 'Nagad', 'Bank Transfer', 'Cash on Delivery'] as $method): ?>
                <label class="payment-option">
                    <input
                        type="radio"
                        name="payment_method"
                        value="<?= htmlspecialchars($method) ?>"
                    >

                    <span>
                        <?= htmlspecialchars($method) ?>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="actions">
            <a class="btn light" href="index.php?page=cart">
                Cancel
            </a>

            <button class="btn" type="submit">
                Confirm Purchase
            </button>
        </div>
    </form>
</section>

<script>
function validatePaymentMethod() {
    let selectedPayment = document.querySelector('input[name="payment_method"]:checked');

    if (selectedPayment === null) {
        alert('Please select payment method');
        return false;
    }

    return true;
}
</script>

<?php require 'views/layout/footer.php'; ?>