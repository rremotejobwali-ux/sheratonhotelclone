<?php
require 'db.php';
$stmt = $pdo->query("SELECT id, name FROM hotels");
echo "<pre>";
print_r($stmt->fetchAll());
echo "</pre>";
?>
