<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
$user = null;
if (isset($_SESSION['user_id'])) {
  $stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE id=?');
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Local Bites</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-row">
    <div class="brand">
      <a href="/index.php">Local<span class="accent">Bites</span></a>
    </div>
    <nav class="main-nav">
      <a href="/index.php">Home</a>
      <a href="/menu.php">Menu</a>
      <a href="/contact.php">Contact</a>
    </nav>
    <div class="nav-icons">
      <a class="icon" href="/cart.php" title="Cart">🛒 <span id="cart-count"><?php echo isset($_SESSION['cart'])? array_sum($_SESSION['cart']): 0; ?></span></a>
      <?php if($user): ?>
        <a class="icon" href="/auth.php?profile=1">👤 <?php echo htmlspecialchars($user['name']); ?></a>
        <a class="icon" href="/logout.php">Logout</a>
      <?php else: ?>
        <a class="icon" href="/auth.php">Login / Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main>
  <section class="hero"></section>
