<?php include __DIR__ . '/../includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'register') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if ($name && $email && $password) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
      try {
        $stmt->execute([$name,$email,$hash]);
        $success = 'Registration successful. Please login.';
      } catch (PDOException $e) {
        $error = 'Email maybe already registered.';
      }
    }
  }
  if ($action === 'login') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u && password_verify($password, $u['password'])) {
      session_start();
      $_SESSION['user_id'] = $u['id'];
      header('Location: /index.php'); exit;
    } else {
      $error = 'Invalid credentials.';
    }
  }
}
?>
<section class="container auth-page">
  <div class="auth-grid">
    <div class="auth-box">
      <h3>Login</h3>
      <?php if(isset($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
      <?php if(isset($success)) echo '<p class="success">'.htmlspecialchars($success).'</p>'; ?>
      <form method="post">
        <input type="hidden" name="action" value="login">
        <input name="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <button class="btn primary">Login</button>
      </form>
    </div>
    <div class="auth-box">
      <h3>Register</h3>
      <form method="post">
        <input type="hidden" name="action" value="register">
        <input name="name" placeholder="Full name" required>
        <input name="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Choose password" required>
        <button class="btn">Register</button>
      </form>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
