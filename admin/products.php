<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: /admin/login.php'); exit;
}
require_once __DIR__ . '/../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
  $name = $_POST['name'] ?? '';
  $cat = (int)($_POST['category_id'] ?? 0);
  $price = (float)($_POST['price'] ?? 0);
  $desc = $_POST['description'] ?? '';
  $image = $_POST['image'] ?? 'placeholder.png';
  if ($name && $cat && $price) {
    $stmt = $pdo->prepare('INSERT INTO products (category_id,name,description,price,image) VALUES (?,?,?, ?, ?)');
    $stmt->execute([$cat,$name,$desc,$price,$image]);
    $msg = 'Product added.';
  }
}
$cats = $pdo->query('SELECT * FROM categories')->fetchAll();
$products = $pdo->query('SELECT p.*, c.name as category FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Manage Products</title><link rel="stylesheet" href="/assets/css/style.css"></head><body>
<div class="container">
  <h1>Products</h1>
  <p><a href="/admin/dashboard.php">Back to dashboard</a></p>
  <?php if(isset($msg)) echo '<p class="success">'.htmlspecialchars($msg).'</p>'; ?>
  <h3>Add Product</h3>
  <form method="post">
    <input name="name" placeholder="Product name" required>
    <select name="category_id" required>
      <option value="">Select category</option>
      <?php foreach($cats as $c) echo '<option value="'. $c['id'] .'">'.htmlspecialchars($c['name']).'</option>'; ?>
    </select>
    <input name="price" placeholder="Price" required>
    <input name="image" placeholder="Image filename (place in assets/images/)">
    <textarea name="description" placeholder="Description"></textarea>
    <button name="add_product" class="btn primary">Add Product</button>
  </form>
  <h3>Existing Products</h3>
  <table class="cart-table"><thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th></tr></thead><tbody>
  <?php foreach($products as $p): ?>
    <tr><td><?php echo $p['id']; ?></td><td><?php echo htmlspecialchars($p['name']); ?></td><td><?php echo htmlspecialchars($p['category']); ?></td><td>₦<?php echo number_format($p['price'],2); ?></td></tr>
  <?php endforeach; ?>
  </tbody></table>
</div>
</body></html>
