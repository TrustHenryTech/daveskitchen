<?php include __DIR__ . '/../includes/header.php';
$stmt = $pdo->query('SELECT * FROM categories ORDER BY id');
$categories = $stmt->fetchAll();
$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
if ($cat) {
  $pstmt = $pdo->prepare('SELECT * FROM products WHERE category_id = ?');
  $pstmt->execute([$cat]);
} else {
  $pstmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
}
$products = $pstmt->fetchAll();
?>
<section class="container menu-page">
  <aside class="menu-cats">
    <h3>Categories</h3>
    <ul>
      <li><a href="/menu.php">All</a></li>
      <?php foreach($categories as $c): ?>
        <li><a href="/menu.php?cat=<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </aside>
  <div class="menu-list">
    <?php if(!$products): ?>
      <p>No items found.</p>
    <?php endif; ?>
    <div class="products-grid">
      <?php foreach($products as $p): ?>
        <div class="product-card">
          <img src="/assets/images/<?php echo $p['image'] ?: 'placeholder.png'; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
          <div class="product-info">
            <h4><?php echo htmlspecialchars($p['name']); ?></h4>
            <p class="desc"><?php echo htmlspecialchars($p['description']); ?></p>
            <div class="product-bottom">
              <span class="price">₦<?php echo number_format($p['price'],2); ?></span>
              <button class="btn add-to-cart" data-id="<?php echo $p['id']; ?>">Add</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
