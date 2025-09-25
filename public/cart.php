<?php include __DIR__ . '/../includes/header.php';
session_start();
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
  $ids = array_keys($cart);
  $placeholders = rtrim(str_repeat('?,', count($ids)), ',');
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
  $stmt->execute($ids);
  $products = $stmt->fetchAll(PDO::FETCH_UNIQUE);
  foreach ($cart as $pid => $qty) {
    if (!isset($products[$pid])) continue;
    $prod = $products[$pid];
    $sub = $prod['price'] * $qty;
    $items[] = ['product'=>$prod, 'qty'=>$qty, 'sub'=>$sub];
    $total += $sub;
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  if ($name && $phone && $address && $items) {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total) VALUES (?,?,?,?,?)');
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt->execute([$user_id, $name, $phone, $address, $total]);
    $order_id = $pdo->lastInsertId();
    $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)');
    foreach ($items as $it) {
      $stmtItem->execute([$order_id, $it['product']['id'], $it['qty'], $it['product']['price']]);
    }
    $pdo->commit();
    unset($_SESSION['cart']);
    $success = 'Order placed successfully! Your order id: ' . $order_id;
  } else {
    $error = 'Please fill all fields and ensure cart is not empty.';
  }
}
?>
<section class="container cart-page">
  <h2>Your Cart</h2>
  <?php if(!empty($items)): ?>
    <table class="cart-table">
      <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
      <tbody>
        <?php foreach($items as $it): ?>
          <tr>
            <td><?php echo htmlspecialchars($it['product']['name']); ?></td>
            <td><?php echo $it['qty']; ?></td>
            <td>₦<?php echo number_format($it['product']['price'],2); ?></td>
            <td>₦<?php echo number_format($it['sub'],2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="cart-total">Total: ₦<?php echo number_format($total,2); ?></div>
    <?php if(isset($success)): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <?php if(isset($error)): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <form method="post" class="checkout-form">
      <input name="name" placeholder="Full name" required />
      <input name="phone" placeholder="Phone number" required />
      <textarea name="address" placeholder="Delivery address" required></textarea>
      <button type="submit" name="checkout" class="btn primary">Place Order</button>
    </form>
  <?php else: ?>
    <p>Your cart is empty. <a href="/menu.php">Browse the menu</a></p>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
