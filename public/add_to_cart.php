<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)$_POST['product_id'];
  $qty = isset($_POST['qty']) ? max(1,(int)$_POST['qty']) : 1;
  $stmt = $pdo->prepare('SELECT id FROM products WHERE id=?');
  $stmt->execute([$id]);
  if ($stmt->fetch()) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 0;
    $_SESSION['cart'][$id] += $qty;
    echo json_encode(['success'=>true,'count'=>array_sum($_SESSION['cart'])]);
  } else {
    echo json_encode(['success'=>false]);
  }
}
