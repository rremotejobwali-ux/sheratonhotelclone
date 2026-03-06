<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Test</h1>";
echo "PHP Version: " . phpversion() . "<br>";

$host = 'localhost';
$db   = 'farhan1_rsk2_18'; 
$user = 'farhan1_rsk2_18'; 
$pass = '123456';

echo "<h2>Testing Connection to $db...</h2>";

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    echo "<p style='color:green'>Success! Account $user can connect to $db.</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>Failed with prefix: " . $e->getMessage() . "</p>";
    
    echo "<h2>Testing Connection to rsk2_18...</h2>";
    try {
        $dsn = "mysql:host=$host;dbname=rsk2_18;charset=utf8mb4";
        $pdo = new PDO($dsn, 'rsk2_18', $pass);
        echo "<p style='color:green'>Success! Account rsk2_18 can connect to rsk2_18 without prefix.</p>";
    } catch (PDOException $e2) {
        echo "<p style='color:red'>Failed without prefix: " . $e2->getMessage() . "</p>";
    }
}
?>
