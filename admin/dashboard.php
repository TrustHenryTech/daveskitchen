<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: /admin/login.php'); exit;
}
require_once __DIR__ . '/../includes/db.php';
// simple stats
$totOrders = $pdo->query('SELECT COUNT(*) as c FROM orders')->fetch()['c'] ?? 0;
$totProducts = $pdo->query('SELECT COUNT(*) as c FROM products')->fetch()['c'] ?? 0;
$totUsers = $pdo->query('SELECT COUNT(*) as c FROM users')->fetch()['c'] ?? 0;
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard</title><link rel="stylesheet" href="/assets/css/style.css"></head><body>
<div class="container">
  <h1>Admin Dashboard</h1>
  <p><a href="/admin/products.php">Manage Products</a> | <a href="/admin/orders.php">View Orders</a> | <a href="/admin/logout.php">Logout</a></p>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem">
    <div class="card"><h3>Total Orders</h3><p><?php echo $totOrders; ?></p></div>
    <div class="card"><h3>Total Products</h3><p><?php echo $totProducts; ?></p></div>
    <div class="card"><h3>Registered Users</h3><p><?php echo $totUsers; ?></p></div>
  </div>
</div>
</body></html>
