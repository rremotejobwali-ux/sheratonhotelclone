<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Permission Check</h1>";
$files = ['index.php', 'db.php', 'error_check.php', 'style.css'];

foreach ($files as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        echo "<p>$file: " . substr(sprintf('%o', $perms), -4) . "</p>";
    } else {
        echo "<p>$file: MISSING</p>";
    }
}

echo "<h2>Server User</h2>";
echo "Current User: " . get_current_user() . "<br>";
echo "Process User: " . posix_getpwuid(posix_geteuid())['name'] . "<br>";
?>
