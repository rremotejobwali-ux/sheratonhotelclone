<?php
/**
 * index.php - SHERATON CLONE
 */

// 1. Force Error Reporting (Very important to see the cause of 500 errors)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    // 2. Load database configuration
    if (!file_exists(__DIR__ . '/db.php')) {
        throw new Exception("File 'db.php' not found. Please ensure it exists.");
    }
    require_once __DIR__ . '/db.php';

    // 3. Double Check Connection (PDO object must exist)
    if (!isset($pdo) || !$pdo) {
        throw new Exception("Database object (\$pdo) not initialized correctly.");
    }

    // 4. Fetch featured hotels for homepage (Top 6)
    $stmt = $pdo->query("SELECT * FROM hotels ORDER BY rating DESC LIMIT 6");
    $featuredHotels = $stmt->fetchAll();

} catch (Exception $e) {
    // 5. Catch and show detailed error if initial setup fails
    die("<div style='background:#fff5f5; color:#c53030; padding:40px; border-radius:8px; border:2px solid #feb2b2; font-family:sans-serif; margin:20px;'>
            <h2 style='margin-top:0;'>Site Maintenance / Setup Required</h2>
            <p style='font-size:1.1rem; line-height:1.6;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <hr style='border:none; border-top:1px solid #feb2b2; margin:20px 0;'>
            <p style='font-size:0.9rem; color:#718096;'>File: " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>
        </div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheraton Hotels & Resorts | Official Site</title>
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

<section class="hero">
    <h1>Where the World Comes Together</h1>
    <form action="list.php" method="GET" class="search-container">
        <div class="input-group">
            <label for="location">Destination</label>
            <input type="text" name="location" id="location" placeholder="Where are you going?" required>
        </div>
        <div class="input-group">
            <label for="checkin">Check-In</label>
            <input type="date" name="checkin" id="checkin" required>
        </div>
        <div class="input-group">
            <label for="checkout">Check-Out</label>
            <input type="date" name="checkout" id="checkout" required>
        </div>
        <div class="input-group" style="flex: 0 0 auto;">
            <label>&nbsp;</label>
            <button type="submit" class="btn-primary">Find Hotels</button>
        </div>
    </form>
</section>

<section class="container">
    <h2 class="section-title">Featured Destinations</h2>
    <div class="hotel-grid">
        <?php if (empty($featuredHotels)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-magnifying-glass fa-3x" style="color: #ccc; margin-bottom: 20px;"></i>
                <h3>No Hotels Found</h3>
                <p>We couldn't find any hotels at the moment. Please check back later.</p>
            </div>
        <?php else: ?>
            <?php foreach($featuredHotels as $hotel): ?>
            <div class="hotel-card">
                <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>" class="hotel-img">
                <div class="hotel-info">
                    <h3 class="hotel-name"><?php echo htmlspecialchars($hotel['name']); ?></h3>
                    <div class="hotel-location">
                        <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($hotel['location']); ?>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div class="hotel-rating">
                            <?php 
                            $stars = round($hotel['rating']);
                            for($i=0; $i<5; $i++) {
                                echo ($i < $stars) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                            <span style="color: #666; font-size: 0.9rem;">(<?php echo $hotel['rating']; ?>/5)</span>
                        </div>
                        <div class="hotel-price">$<?php echo number_format($hotel['price_per_night'], 0); ?> <span style="font-size: 0.8rem; font-weight: 400;">/ night</span></div>
                    </div>
                    <p style="font-size: 0.9rem; color: #555; margin-bottom: 1rem; line-height: 1.4;">
                        <?php echo substr(htmlspecialchars($hotel['description']), 0, 80) . '...'; ?>
                    </p>
                    <div class="card-footer">
                        <a href="details.php?id=<?php echo $hotel['id']; ?>" class="btn-primary btn-sm" style="text-decoration: none;">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="container" style="margin-top: 4rem; text-align: center; background: #fff; padding: 4rem; border-radius: 8px;">
    <h2 class="section-title">Join Marriott Bonvoy™</h2>
    <p style="margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
        Unlock extraordinary experiences. Earn points towards free nights and more with every stay.
    </p>
    <a href="signup.php" class="btn-primary">Join for Free</a>
</section>

<footer>
    <div class="footer-links">
        <a href="#">About Us</a>
        <a href="#">Careers</a>
        <a href="#">News</a>
        <a href="#">Investor Relations</a>
    </div>
    <p>&copy; 2026 Sheraton Hotels & Resorts. All rights reserved.</p>
</footer>

<script src="script.js"></script>
</body>
</html>
