<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debugging from test_minimal.php</h1>";

if (file_exists('.htaccess')) {
    echo "<h3>.htaccess found:</h3>";
    echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
} else {
    echo "<h3>.htaccess NOT found in " . getcwd() . "</h3>";
}

echo "<h3>Directory Listing:</h3>";
echo "<pre>";
print_r(scandir('.'));
echo "</pre>";
?>
