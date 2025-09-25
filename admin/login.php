<?php
session_start();
$cfg = require __DIR__ . '/config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  if ($u === $cfg['username'] && $p === $cfg['password']) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: /admin/dashboard.php'); exit;
  } else {
    $error = 'Invalid admin credentials.';
  }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link rel="stylesheet" href="/assets/css/style.css"></head><body>
<div class="container" style="max-width:480px;margin-top:2rem">
<h2>Admin Login</h2>
<?php if($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <input name="username" placeholder="Username" required>
  <input name="password" type="password" placeholder="Password" required>
  <button class="btn primary">Login</button>
</form>
</div>
</body></html>
