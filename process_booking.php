<?php
// process_booking.php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_id = $_POST['hotel_id'];
    $user_name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $check_in = $_POST['checkin'];
    $check_out = $_POST['checkout'];
    $total_price = $_POST['total_price'];
    $user_id = $_SESSION['user_id'] ?? null;

    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (hotel_id, user_id, user_name, email, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$hotel_id, $user_id, $user_name, $email, $check_in, $check_out, $total_price]);
        
        $booking_id = $pdo->lastInsertId();
        header("Location: confirmation.php?id=$booking_id");
        exit;
    } catch (PDOException $e) {
        die("Booking failed: " . $e->getMessage());
    }
} else {
    header("Location: list.php");
    exit;
}
?>
