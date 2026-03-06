<?php
// details.php
require 'db.php';

$id = $_GET['id'] ?? null;
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';

if (!$id) {
    header("Location: list.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->execute([$id]);
$hotel = $stmt->fetch();

if (!$hotel) {
    echo "Hotel not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($hotel['name']); ?> | Sheraton Official</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-hotel"></i> SHERATON 
            <span style="font-size: 0.8rem; font-weight: 300; display: block; letter-spacing: 3px; margin-top: -5px;">HOTELS & RESORTS</span>
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="list.php">Find a Hotel</a></li>
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                <li><a href="#"><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars((string)$_SESSION['username']); ?></a></li>
                <li><a href="logout.php" class="btn-primary btn-sm" style="color:white; padding: 5px 15px;">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php" class="btn-primary btn-sm" style="color:white; padding: 5px 15px;">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div style="height: 50vh; overflow: hidden; position: relative;">
    <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Hotel Image">
    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.7)); padding: 2rem; color: white;">
        <div class="container">
            <h1 style="text-shadow: 0 2px 4px rgba(0,0,0,0.5); font-family: 'Playfair Display', serif;"><?php echo htmlspecialchars($hotel['name']); ?></h1>
            <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($hotel['location']); ?></p>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 2rem;">
    <div class="layout-flex">
        <div style="flex: 2;">
            <h2 class="section-title" style="text-align: left;">Overview</h2>
            <p style="font-size: 1.1rem; color: #444; margin-bottom: 2rem;">
                <?php echo htmlspecialchars($hotel['description']); ?>
            </p>

            <h3>Amenities</h3>
            <ul style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1rem; list-style: none;">
                <?php 
                $amenities = explode(',', $hotel['amenities']);
                foreach($amenities as $amenity): 
                ?>
                    <li><i class="fa-solid fa-check" style="color: var(--success);"></i> <?php echo trim($amenity); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div style="flex: 1;">
            <div class="booking-form" style="position: sticky; top: 100px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Book Your Stay</h3>
                <div style="margin-bottom: 1rem;">
                    <span style="font-size: 2rem; font-weight: 700;">$<?php echo number_format($hotel['price_per_night']); ?></span>
                    <span style="color: #666;">/ night</span>
                </div>

                <form action="booking.php" method="GET">
                    <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
                    <div class="form-group">
                        <label>Check-In</label>
                        <input type="date" name="checkin" class="form-control" value="<?php echo htmlspecialchars($checkin); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Check-Out</label>
                        <input type="date" name="checkout" class="form-control" value="<?php echo htmlspecialchars($checkout); ?>" required>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button type="submit" class="btn-primary" style="width: 100%;">Reserve Now</button>
                    <?php else: ?>
                        <a href="login.php" class="btn-primary" style="width: 100%; display: block; text-align: center; text-decoration: none;">Login to Book</a>
                    <?php endif; ?>
                    <p style="text-align: center; margin-top: 1rem; font-size: 0.8rem; color: #666;">You won't be charged yet</p>
                </form>
            </div>
        </div>
    </div>
</div>

<footer style="margin-top: 4rem;">
    <div class="container" style="text-align: center; color: white;">
        <p>&copy; 2024 Sheraton Hotels & Resorts. All rights reserved.</p>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>
