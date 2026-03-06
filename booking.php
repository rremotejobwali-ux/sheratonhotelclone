<?php
// booking.php
require 'db.php';

$hotel_id = $_GET['hotel_id'] ?? null;
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';

if (!$hotel_id || !$checkin || !$checkout) {
    header("Location: list.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch();

if (!$hotel) {
    die("Hotel not found.");
}

// Calculate days
$d1 = new DateTime($checkin);
$d2 = new DateTime($checkout);
$interval = $d1->diff($d2);
$days = $interval->days > 0 ? $interval->days : 1;
$total_price = $days * $hotel['price_per_night'];

// Pre-fill user data if logged in
$user_name = $_SESSION['username'] ?? '';
$user_email = '';
if (isset($_SESSION['user_id'])) {
    $stmtUser = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $uData = $stmtUser->fetch();
    $user_email = $uData['email'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Details | Sheraton</title>
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

<div class="container" style="margin-top: 3rem;">
    <h2 class="section-title">Review Your Stay</h2>
    
    <div class="booking-container">
        <div class="booking-form">
            <h3>Guest Information</h3>
            <form action="process_booking.php" method="POST">
                <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
                <input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
                <input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="user_name" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="user_email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" required>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h3>Payment Details</h3>
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" class="form-control" placeholder="0000 0000 0000 0000" disabled value="Demo Mode - No Charge">
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 1rem;">Confirm & Pay</button>
            </form>
        </div>

        <div class="booking-summary">
            <h3>Reservation Summary</h3>
            <div style="display: flex; gap: 1rem; margin: 1.5rem 0;">
                <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                <div>
                    <h4 style="color: var(--primary-color);"><?php echo htmlspecialchars($hotel['name']); ?></h4>
                    <p style="font-size: 0.9rem; color: #666;"><?php echo htmlspecialchars($hotel['location']); ?></p>
                </div>
            </div>
            
            <div style="border-top: 1px solid #eee; padding-top: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>$<?php echo number_format($hotel['price_per_night']); ?> x <?php echo $days; ?> nights</span>
                    <span>$<?php echo number_format($total_price); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Service Fee</span>
                    <span>Free</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.2rem; margin-top: 1rem; color: var(--primary-color);">
                    <span>Total (USD)</span>
                    <span>$<?php echo number_format($total_price); ?></span>
                </div>
            </div>

            <div style="margin-top: 1.5rem; background: #f9f9f9; padding: 1rem; border-radius: 8px; font-size: 0.9rem;">
                <p><strong>Check-In:</strong> <?php echo date('D, M j, Y', strtotime($checkin)); ?></p>
                <p><strong>Check-Out:</strong> <?php echo date('D, M j, Y', strtotime($checkout)); ?></p>
            </div>
        </div>
    </div>
</div>

<footer style="margin-top: 4rem;">
    <p>&copy; 2024 Sheraton Hotels & Resorts. All rights reserved.</p>
</footer>

</body>
</html>
