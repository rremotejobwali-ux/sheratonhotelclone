<?php
echo "<h1>Remote File List</h1>";
$files = scandir(__DIR__);
echo "<pre>";
foreach($files as $file) {
    $perms = fileperms($file);
    echo str_pad($file, 30) . " | " . sprintf('%o', $perms) . "\n";
}
echo "</pre>";
?>
