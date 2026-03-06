<?php
/**
 * db.php - SHERATON CLONE
 * Robust database connection with error reporting.
 */

// 1. Force Error Reporting (Crucial for 500 errors)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// 2. Configuration
$db_host = 'localhost';
$db_pass = '123456';

// Potential configurations - prioritizing the new one provided: rsk2_20
$potential_configs = [
    ['db' => 'farhan1_rsk2_20', 'user' => 'farhan1_rsk2_20'],
    ['db' => 'rsk0_rsk0277_rsk2_20', 'user' => 'rsk0_rsk0277_rsk2_20'],
    ['db' => 'rsk2_20', 'user' => 'rsk2_20'],
    ['db' => 'farhan1_rsk2_19', 'user' => 'farhan1_rsk2_19'],
    ['db' => 'farhan1_rsk2_18', 'user' => 'farhan1_rsk2_18'],
    ['db' => 'rsk0_rsk0277_rsk2_19', 'user' => 'rsk0_rsk0277_rsk2_19'],
    ['db' => 'rsk0_rsk0277_2', 'user' => 'rsk0_rsk0277_2'],
    ['db' => 'rsk2_19', 'user' => 'rsk2_19']
];

$pdo = null;
$db_error = "No connection attempted";

// 3. Try Connecting
foreach ($potential_configs as $config) {
    try {
        $dsn = "mysql:host=$db_host;dbname={$config['db']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['user'], $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 2
        ]);
        $db_error = null;
        break; 
    } catch (PDOException $e) {
        $db_error = $e->getMessage();
        continue;
    }
}

// 4. Handle Failure
if (!$pdo) {
    echo "<div style='background:#fff0f0; color:#c00; padding:30px; border:2px solid #c00; font-family:sans-serif; margin:20px; border-radius:10px;'>";
    echo "<h1>Database Connection Failed</h1>";
    echo "<p>We tried multiple database names but all failed.</p>";
    echo "<p><strong>Error Message:</strong> " . htmlspecialchars($db_error) . "</p>";
    echo "<p style='font-size:0.9rem;'>Ensure you have created the database and user in cPanel with password <code>123456</code>.</p>";
    echo "</div>";
    exit;
}

// 5. Create Tables and Seed Data
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        username VARCHAR(100) UNIQUE, 
        email VARCHAR(150) UNIQUE, 
        password VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS hotels (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255), 
        location VARCHAR(255), 
        description TEXT, 
        price_per_night DECIMAL(10,2), 
        rating DECIMAL(3,1), 
        image_url TEXT, 
        amenities TEXT
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        hotel_id INT, 
        user_id INT, 
        user_name VARCHAR(255), 
        email VARCHAR(255), 
        check_in DATE, 
        check_out DATE, 
        total_price DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $count = $pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn();
    if ($count == 0 || isset($_GET['reseed'])) {
        if (isset($_GET['reseed'])) { $pdo->exec("DELETE FROM hotels"); }
        $pdo->exec("INSERT INTO hotels (name, location, description, price_per_night, rating, image_url, amenities) VALUES
        ('Sheraton Grand Los Angeles', 'Los Angeles, CA', 'Experience the glamour of LA at our downtown hotel with stunning city views and world-class service.', 250.00, 4.8, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80', 'Free WiFi, Pool, Gym, Spa, Valet Parking'),
        ('Sheraton New York Times Square', 'New York, NY', 'Stay in the heart of NYC, steps away from Broadway, Central Park, and the iconic Times Square.', 320.00, 4.6, 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80', 'Free WiFi, Restaurant, Bar, Concierge, Business Center'),
        ('Sheraton Maldives Full Moon Resort', 'Maldives', 'A tropical paradise featuring overwater bungalows, pristine white sands, and crystal clear lagoons.', 650.00, 4.9, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80', 'Beach Access, Spa, Water Sports, All-inclusive, Private Pool'),
        ('Sheraton London Park Lane', 'London, UK', 'Art Deco elegance in the heart of Mayfair, overlooking Green Park with historic charm.', 400.00, 4.7, 'https://images.unsplash.com/photo-1551882547-ff43c63efe81?auto=format&fit=crop&w=1200&q=80', 'Afternoon Tea, Gym, Meeting Rooms, Pet Friendly, Free WiFi'),
        ('Sheraton Tokyo Bay Hotel', 'Tokyo, Japan', 'The official hotel of Tokyo Disney Resort with spacious rooms and breathtaking ocean views.', 280.00, 4.5, 'https://images.unsplash.com/photo-1590490359683-658d3d23f972?auto=format&fit=crop&w=1200&q=80', 'Shuttle to Disney, Pool, Kids Club, Garden, 5 Restaurants'),
        ('Sheraton Grand Hotel Dubai', 'Dubai, UAE', 'A towering symbol of luxury on Sheikh Zayed Road, featuring a rooftop infinity pool and desert views.', 350.00, 4.9, 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80', 'Infinity Pool, Rooftop Bar, Luxury Spa, 24/7 Gym'),
        ('Sheraton Paris Airport Hotel', 'Paris, France', 'Elegance meets convenience at Charles de Gaulle Airport, perfect for international travelers.', 220.00, 4.4, 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1200&q=80', 'Soundproof Rooms, High-speed WiFi, Fine Dining, Lounge'),
        ('Sheraton Santorini Resort', 'Santorini, Greece', 'Stunning Cycladic architecture with Caldera views and private balconies for watching the sunset.', 500.00, 4.8, 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1200&q=80', 'Sea View, Breakfast Included, Spa, Terrace, Bar')");
    }
} catch (PDOException $e) {
    // Silent fail if table already exists or seed fails
}

// 6. Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
