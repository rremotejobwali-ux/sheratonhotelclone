<?php
/**
 * clean_install.php
 * Run this to manually reset and create all tables.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Starting Clean Install...</h1>";

$host = 'localhost';
$user = 'farhan1_rsk2_18'; 
$pass = '123456';
$db   = 'farhan1_rsk2_18'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connected to $db</p>";

    // Sheraton Tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(100), email VARCHAR(150), password VARCHAR(255))");
    $pdo->exec("CREATE TABLE IF NOT EXISTS hotels (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), location VARCHAR(255), description TEXT, price_per_night DECIMAL(10,2), rating DECIMAL(3,1), image_url TEXT, amenities TEXT)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (id INT AUTO_INCREMENT PRIMARY KEY, hotel_id INT, user_id INT, user_name VARCHAR(255), email VARCHAR(255), check_in DATE, check_out DATE, total_price DECIMAL(10,2))");
    
    echo "<p style='color:green'>Tables Created Successfully!</p>";

} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>
