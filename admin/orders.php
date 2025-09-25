<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: /admin/login.php'); exit;
}
require_once __DIR__ . '/../includes/db.php';
$orders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Orders</title><link rel="stylesheet" href="/assets/css/style.css"></head><body>
<div class="container">
  <h1>Orders</h1>
  <p><a href="/admin/dashboard.php">Back to dashboard</a></p>
  <table class="cart-table"><thead><tr><th>ID</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody>
  <?php foreach($orders as $o): ?>
    <tr>
      <td><?php echo $o['id']; ?></td>
      <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
      <td><?php echo htmlspecialchars($o['customer_phone']); ?></td>
      <td>₦<?php echo number_format($o['total'],2); ?></td>
      <td><?php echo htmlspecialchars($o['status']); ?></td>
      <td><?php echo $o['created_at']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table>
</div>
</body></html>
