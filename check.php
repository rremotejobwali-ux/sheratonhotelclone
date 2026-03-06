<?php
// Super Simple DB Test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><body style='font-family:sans-serif; padding:40px;'>";
echo "<h1>System Health Check</h1>";

// 1. Check PHP Version
echo "<p>PHP Version: " . phpversion() . "</p>";

// 2. Check for PDO
if (!class_exists('PDO')) {
    echo "<p style='color:red'>FAILED: PDO Class not found. Please enable it in cPanel PHP Selector.</p>";
} else {
    echo "<p style='color:green'>SUCCESS: PDO Class exists.</p>";
}

// 3. Try Connection
$h = 'localhost';
$u = 'farhan1_rsk2_18';
$p = '123456';
$d = 'farhan1_rsk2_18';

echo "<h2>Testing Connection to $d...</h2>";

try {
    $dsn = "mysql:host=$h;dbname=$d;charset=utf8mb4";
    $test_pdo = new PDO($dsn, $u, $p, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "<p style='color:green'>SUCCESS: Connected to database $d!</p>";
} catch (Exception $e) {
    echo "<p style='color:orange'>Failed with prefix: " . $e->getMessage() . "</p>";
    echo "<h3>Testing without prefix...</h3>";
    try {
        $dsn2 = "mysql:host=$h;dbname=rsk2_18;charset=utf8mb4";
        $test_pdo2 = new PDO($dsn2, 'rsk2_18', $p, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "<p style='color:green'>SUCCESS: Connected to database rsk2_18 without prefix!</p>";
    } catch (Exception $e2) {
        echo "<p style='color:red'>FAILED: Both attempts failed. Please check your DB credentials in cPanel.</p>";
    }
}

echo "</body></html>";
?>
