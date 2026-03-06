<?php
// error_check.php
// Highly robust error reporting script to diagnose database and server issues.

// 1. Force error reporting at the highest level
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<html><body style='font-family:sans-serif; padding:20px; line-height:1.6; background-color: #f4f7f6;'>";
echo "<div style='max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>";
echo "<h1 style='color:#003057; border-bottom: 2px solid #003057; padding-bottom: 10px;'>Sheraton Clone Debugger</h1>";

// 2. Check PHP Environment
echo "<h3>1. PHP Environment</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>OS:</strong> " . PHP_OS . "</li>";
echo "<li><strong>Server Software:</strong> " . (isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown') . "</li>";
echo "<li><strong>Document Root:</strong> " . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'Unknown') . "</li>";
echo "</ul>";

// 3. Test Database Connectivity
echo "<h3>2. Database Connection Test</h3>";

$host = 'localhost';
$pass = '123456';

// List of potential databases to try, common in this environment
$potential_configs = [
    ['db' => 'rsk2_20', 'user' => 'rsk2_20'],
    ['db' => 'farhan1_rsk2_20', 'user' => 'farhan1_rsk2_20'],
    ['db' => 'rsk2_19', 'user' => 'rsk2_19'],
    ['db' => 'farhan1_rsk2_19', 'user' => 'farhan1_rsk2_19'],
    ['db' => 'rsk2_18', 'user' => 'rsk2_18'],
    ['db' => 'farhan1_rsk2_18', 'user' => 'farhan1_rsk2_18'],
    ['db' => 'rsk2_13', 'user' => 'rsk2_13'], // The one previously in this file
    ['db' => 'rsk0_rsk0277_rsk2_20', 'user' => 'rsk0_rsk0277_rsk2_20'],
    ['db' => 'rsk0_rsk0277_2', 'user' => 'rsk0_rsk0277_2']
];

$found_working = false;

foreach ($potential_configs as $config) {
    $db = $config['db'];
    $user = $config['user'];
    
    try {
        echo "<p style='margin: 5px 0;'>Testing <code>$db</code> ... ";
        
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2
        ];
        
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        echo "<span style='color:green; font-weight:bold;'>SUCCESS</span></p>";
        
        echo "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin-top: 10px;'>";
        echo "<strong>WORKING CONFIGURATION FOUND!</strong><br>";
        echo "Database: <code>$db</code><br>";
        echo "User: <code>$user</code>";
        echo "</div>";

        // Check for tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Tables found:</strong> " . (empty($tables) ? "None" : implode(", ", $tables)) . "</p>";
        
        if (empty($tables)) {
            echo "<p style='color: orange;'><strong>Warning:</strong> The database is connected but has no tables. You may need to import <code>database.sql</code>.</p>";
        }

        $found_working = true;
        break; // Stop after finding the first working one

    } catch (PDOException $e) {
        echo "<span style='color:red;'>Failed</span> (" . htmlspecialchars($e->getMessage()) . ")</p>";
    }
}

if (!$found_working) {
    echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; border:1px solid #f5c6cb; margin-top: 20px;'>";
    echo "<strong>NO WORKING CONNECTION FOUND.</strong><br><br>";
    echo "<strong>Common Fixes:</strong><br>";
    echo "1. Go to cPanel > MySQL Databases.<br>";
    echo "2. Create a database named <code>rsk2_20</code> (or check which one you have).<br>";
    echo "3. Create a user <code>rsk2_20</code> with password <code>123456</code>.<br>";
    echo "4. <strong>CRITICAL:</strong> Add the user to the database with ALL PRIVILEGES.<br>";
    echo "5. Note if your cPanel adds a prefix like <code>farhan1_</code> to your names.";
    echo "</div>";
}

// 4. File Integrity Check
echo "<h3>3. Files on Server</h3>";
$critical_files = ['index.php', 'db.php', 'style.css', 'list.php', 'database.sql'];
echo "<ul>";
foreach ($critical_files as $file) {
    $exists = file_exists($file);
    echo "<li>$file: " . ($exists ? "<span style='color:green;'>Found</span>" : "<span style='color:red;'>Missing</span>") . "</li>";
}
echo "</ul>";

echo "</div>"; // End container
echo "</body></html>";
?>
