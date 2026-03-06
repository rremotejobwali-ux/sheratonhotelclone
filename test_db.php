<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Checking Database Connection...<br>";

$host = 'localhost';
$user = 'farhan1_rsk2_18';
$pass = '123456';
$db   = 'farhan1_rsk2_18';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    echo "Connection Successful!";
} catch (Exception $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
