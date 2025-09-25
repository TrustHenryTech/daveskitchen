<?php
$host = 'localhost';
$db = 'restaurant_db';
$user = 'db_user';
$pass = 'db_pass';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
$pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
exit('DB Connection failed: ' . $e->getMessage());
}
