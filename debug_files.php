<?php
echo "Current directory: " . getcwd() . "<br>";
echo "Files in current directory:<br>";
$files = scandir('.');
foreach ($files as $file) {
    echo $file . "<br>";
}
?>
