<?php
// confirmation.php
require 'db.php';

$booking_id = $_GET['id'] ?? null;

if (!$booking_id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT b.*, h.name as hotel_name, h.location as hotel_location, h.image_url 
                       FROM bookings b 
                       JOIN hotels h ON b.hotel_id = h.id 
                       WHERE b.id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
    die("Booking not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed | Sheraton</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-hotel"></i> SHERATON 
        </a>
    </nav>
</header>

<div class="container" style="margin-top: 5rem; text-align: center;">
    <div style="background: white; padding: 4rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
        <i class="fa-solid fa-circle-check fa-5x" style="color: var(--success); margin-bottom: 1.5rem;"></i>
        <h1 style="font-family: 'Playfair Display', serif; color: var(--primary-color); margin-bottom: 1rem;">Booking Confirmed!</h1>
        <p style="font-size: 1.2rem; color: #555; margin-bottom: 2rem;">Thank you for choosing Sheraton. Your reservation has been successfully processed.</p>
        
        <div style="text-align: left; background: #f9f9f9; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Reservation Details</h3>
            <p><strong>Confirmation Number:</strong> #SH-<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></p>
            <p><strong>Hotel:</strong> <?php echo htmlspecialchars($booking['hotel_name']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($booking['hotel_location']); ?></p>
            <p><strong>Guest Name:</strong> <?php echo htmlspecialchars($booking['user_name']); ?></p>
            <hr style="margin: 1rem 0; border: none; border-top: 1px solid #ddd;">
            <p><strong>Check-In:</strong> <?php echo date('D, M j, Y', strtotime($booking['check_in'])); ?></p>
            <p><strong>Check-Out:</strong> <?php echo date('D, M j, Y', strtotime($booking['check_out'])); ?></p>
            <p style="font-size: 1.2rem; margin-top: 1rem; color: var(--primary-color);"><strong>Total Paid:</strong> $<?php echo number_format($booking['total_price']); ?></p>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="window.print()" class="btn-primary" style="background: #666;"><i class="fa-solid fa-print"></i> Print Receipt</button>
            <a href="index.php" class="btn-primary">Back to Home</a>
        </div>
    </div>
</div>

<footer style="margin-top: 5rem;">
    <p>&copy; 2024 Sheraton Hotels & Resorts. All rights reserved.</p>
</footer>

</body>
</html>
