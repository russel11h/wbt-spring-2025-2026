<?php
// This admin view shows accepted order history.
// Unit price and total price are shown side by side with transaction ID.

$title  = 'Order History';
$orders = $orders ?? [];

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>Admin Order History</h1>
    <p>Only accepted orders are shown here.</p>
</section>

<?php if (empty($orders)): ?>
    <div class="alert error">
        No accepted order found.
    </div>
<?php endif; ?>

<?php foreach ($orders as $o): ?>
    <?php
        $items = $o['items'] ?? [];

        $payment = $o['payment'] ?? [
            'transaction_id' => ''
        ];

        $transactionId = $payment['transaction_id'] ?: 'N/A';
    ?>

    <section class="card order-card">
        <h3>
            Order #<?= htmlspecialchars($o['id']) ?>
            -
            <?= htmlspecialchars($o['customer_name']) ?>
        </h3>

        <p>
            <b>Total:</b>
            Tk <?= number_format($o['total_amount'], 2) ?>

            |

            <b>Transaction ID:</b>
            <?= htmlspecialchars($transactionId) ?>

            |

            <b>Date:</b>
            <?= htmlspecialchars($o['order_date']) ?>
        </p>

        <div class="table-card no-padding">
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($items as $it): ?>
                        <?php
                            $line = $it['quantity'] * $it['unit_price'];
                        ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars($it['medicine_name']) ?>
                            </td>

                            <td>
                                <?= intval($it['quantity']) ?>
                            </td>

                            <td>
                                Tk <?= number_format($it['unit_price'], 2) ?>
                            </td>

                            <td>
                                <b>
                                    Tk <?= number_format($line, 2) ?>
                                </b>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endforeach; ?>

<?php require 'views/layout/footer.php'; ?>