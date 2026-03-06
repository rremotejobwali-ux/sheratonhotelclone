<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Error Log Search</h1>";
$paths = [
    'error_log',
    '../error_log',
    '../../error_log',
    'logs/error_log',
    '.logs/error_log',
    'php_error.log'
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        echo "<p>Found <strong>$path</strong> (" . filesize($path) . " bytes)</p>";
        $lines = array_slice(file($path), -20);
        echo "<pre style='background:#eee; padding:10px;'>" . implode("", $lines) . "</pre>";
    } else {
        echo "<p>$path: Not found</p>";
    }
}
?>
