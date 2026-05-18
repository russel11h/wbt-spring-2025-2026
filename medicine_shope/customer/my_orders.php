<?php
// This customer view shows customer order history.
// Unit price, total price and transaction ID are included in every order.

$title  = 'My Orders';
$orders = $orders ?? [];

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>My Orders</h1>
    <p>Your purchase history and transaction information.</p>
</section>

<?php if (isset($_GET['success'])): ?>
    <div class="alert success">
        Purchase confirmed. Order is pending admin approval.
    </div>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div class="alert error">
        No order history found.
    </div>
<?php endif; ?>

<?php foreach ($orders as $o): ?>
    <?php
        $items = $o['items'] ?? [];

        $payment = $o['payment'] ?? [
            'payment_method' => '',
            'transaction_id' => ''
        ];

        $paymentMethod = $payment['payment_method'] ?: ($o['payment_method'] ?? '');
        $transactionId = $payment['transaction_id'] ?: 'N/A';
    ?>

    <section class="card order-card">
        <h3>
            Order #<?= htmlspecialchars($o['id']) ?>
            -
            <?= htmlspecialchars(ucfirst($o['status'])) ?>
        </h3>

        <p>
            <b>Order Total:</b>
            Tk <?= number_format($o['total_amount'], 2) ?>

            |

            <b>Payment:</b>
            <?= htmlspecialchars($paymentMethod) ?>

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
                        <th>Quantity</th>
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