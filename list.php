<?php
// list.php
require 'db.php';

// Search Parameters
$location = $_GET['location'] ?? '';
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$price_max = $_GET['price_max'] ?? 1000;
$min_rating = $_GET['min_rating'] ?? 0;
$sort = $_GET['sort'] ?? 'price_asc';

// Build Query
$sql = "SELECT * FROM hotels WHERE 1=1";
$params = [];

if ($location) {
    $sql .= " AND (location LIKE ? OR name LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}

if ($price_max) {
    $sql .= " AND price_per_night <= ?";
    $params[] = $price_max;
}

if ($min_rating) {
    $sql .= " AND rating >= ?";
    $params[] = $min_rating;
}

// Sorting logic
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY price_per_night ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price_per_night DESC";
        break;
    case 'rating_desc':
        $sql .= " ORDER BY rating DESC";
        break;
    default:
        $sql .= " ORDER BY price_per_night ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hotels = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find a Hotel | Sheraton Official</title>
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

<div class="container layout-flex">
    <!-- Sidebar Filters -->
    <aside class="filters-sidebar">
        <form action="list.php" method="GET">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
            <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
            <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">

            <div class="filter-group">
                <h4>Sort By</h4>
                <select name="sort" class="form-control" onchange="this.form.submit()">
                    <option value="price_asc" <?php if($sort == 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php if($sort == 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
                    <option value="rating_desc" <?php if($sort == 'rating_desc') echo 'selected'; ?>>Top Rated</option>
                </select>
            </div>

            <div class="filter-group">
                <h4>Max Price / Night</h4>
                <input type="range" name="price_max" min="100" max="1000" step="50" value="<?php echo $price_max; ?>" oninput="this.nextElementSibling.value = '$' + this.value">
                <output>$<?php echo $price_max; ?></output>
            </div>

            <div class="filter-group">
                <h4>Minimum Rating</h4>
                <label><input type="radio" name="min_rating" value="4" <?php if($min_rating == 4) echo 'checked'; ?>> 4+ Stars</label><br>
                <label><input type="radio" name="min_rating" value="3" <?php if($min_rating == 3) echo 'checked'; ?>> 3+ Stars</label><br>
                <label><input type="radio" name="min_rating" value="0" <?php if($min_rating == 0) echo 'checked'; ?>> Any</label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%">Update Results</button>
        </form>
    </aside>

    <!-- Search Results -->
    <main class="listings-content">
        <h2 class="section-title" style="text-align: left;">Available Hotels</h2>
        
        <?php if (count($hotels) > 0): ?>
            <div class="hotel-grid" style="grid-template-columns: 1fr;"> <!-- Vertical list layout for search -->
                <?php foreach($hotels as $hotel): ?>
                <div class="hotel-card" style="display: flex; flex-direction: row; margin-bottom: 2rem;">
                    <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>" class="hotel-img" style="width: 300px; height: auto;">
                    <div class="hotel-info" style="flex: 1;">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <h3 class="hotel-name"><?php echo htmlspecialchars($hotel['name']); ?></h3>
                                <div class="hotel-location"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($hotel['location']); ?></div>
                            </div>
                            <div style="text-align: right;">
                                <div class="hotel-price">$<?php echo number_format($hotel['price_per_night']); ?></div>
                                <div style="font-size: 0.8rem;">per night</div>
                            </div>
                        </div>
                        
                        <div class="hotel-rating" style="margin: 1rem 0;">
                            <?php 
                            $stars = round($hotel['rating']);
                            for($i=0; $i<5; $i++) {
                                echo ($i < $stars) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                            <span><?php echo $hotel['rating']; ?> / 5</span>
                        </div>

                        <p style="margin-bottom: 1rem; color: #555;"><?php echo htmlspecialchars($hotel['description']); ?></p>
                        
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                            <?php 
                            $amenities = explode(',', $hotel['amenities']);
                            foreach($amenities as $amenity): 
                            ?>
                                <span style="background: #f0f0f0; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;"><?php echo trim($amenity); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <div style="text-align: right;">
                            <a href="details.php?id=<?php echo $hotel['id']; ?>&checkin=<?php echo $checkin; ?>&checkout=<?php echo $checkout; ?>" class="btn-primary">View Rates</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 100px 20px; background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-top: 20px;">
                <i class="fa-solid fa-search-minus fa-4x" style="color: #ddd; margin-bottom: 25px; display: block;"></i>
                <h3 style="font-size: 1.8rem; color: #33100c; margin-bottom: 15px;">No Found Results</h3>
                <p style="color: #777; font-size: 1.1rem; max-width: 500px; margin: 0 auto;">We couldn't find any hotels matching your criteria. Try adjusting your filters or destination.</p>
                <a href="list.php" class="btn-primary" style="display: inline-block; margin-top: 30px; text-decoration: none;">Clear All Filters</a>
            </div>
        <?php endif; ?>
    </main>
</div>

<footer>
    <p>&copy; 2024 Sheraton Hotels & Resorts. All rights reserved.</p>
</footer>

<script src="script.js"></script>
</body>
</html>
