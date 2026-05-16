<?php
// This admin view shows all purchase requests.
// Admin can accept or reject pending orders using AJAX.
$title = 'Purchase Requests';
$orders = $orders ?? [];
require 'views/layout/header.php';
?>
<section class="page-title-box"><h1>Purchase Requests</h1><p>Accept or reject customer medicine orders.</p></section>
<section class="card table-card">
<table>
    <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Transaction ID</th><th>Address</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach($orders as $o): $payment=$o['payment'] ?? ['transaction_id'=>'']; ?>
        <tr id="order<?= $o['id'] ?>"><td>#<?= $o['id'] ?></td><td><?= htmlspecialchars($o['customer_name']) ?><br><small><?= htmlspecialchars($o['email']) ?></small></td><td>Tk <?= number_format($o['total_amount'],2) ?></td><td><?= htmlspecialchars($payment['transaction_id'] ?: 'N/A') ?></td><td><?= htmlspecialchars($o['shipping_address']) ?></td><td><?= htmlspecialchars($o['order_date']) ?></td><td class="status badge <?= htmlspecialchars($o['status']) ?>"><?= htmlspecialchars($o['status']) ?></td><td><?php if($o['status']==='pending'): ?><button class="small-btn" onclick="statusUpdate(<?= $o['id'] ?>,'accepted')">Accept</button><button class="small-btn danger" onclick="statusUpdate(<?= $o['id'] ?>,'rejected')">Reject</button><?php else: ?>Done<?php endif; ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
</section>
<script>
function statusUpdate(id,status){
    let fd=new FormData();fd.append('order_id',id);fd.append('status',status);
    fetch('index.php?page=ajax_order_status',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{if(d.ok){location.reload();}else alert('Status update failed');});
}
</script>
<?php require 'views/layout/footer.php'; ?>
