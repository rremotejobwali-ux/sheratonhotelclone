<?php
// data_seeder.php
require 'db.php';

echo "<h1>Data Seeder</h1>";

try {
    // 1. Clear existing hotels
    $pdo->exec("DELETE FROM hotels");
    echo "Existing hotels cleared.<br>";

    // 2. Insert new hotels with high-quality images
    $hotels = [
        ['Sheraton Grand Los Angeles', 'Los Angeles, CA', 'Experience the glamour of LA at our downtown hotel with stunning city views and world-class service.', 250.00, 4.8, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80', 'Free WiFi, Pool, Gym, Spa, Valet Parking'],
        ['Sheraton New York Times Square', 'New York, NY', 'Stay in the heart of NYC, steps away from Broadway, Central Park, and the iconic Times Square.', 320.00, 4.6, 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80', 'Free WiFi, Restaurant, Bar, Concierge, Business Center'],
        ['Sheraton Maldives Full Moon Resort', 'Maldives', 'A tropical paradise featuring overwater bungalows, pristine white sands, and crystal clear lagoons.', 650.00, 4.9, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80', 'Beach Access, Spa, Water Sports, All-inclusive, Private Pool'],
        ['Sheraton London Park Lane', 'London, UK', 'Art Deco elegance in the heart of Mayfair, overlooking Green Park with historic charm.', 400.00, 4.7, 'https://images.unsplash.com/photo-1551882547-ff43c63efe81?auto=format&fit=crop&w=1200&q=80', 'Afternoon Tea, Gym, Meeting Rooms, Pet Friendly, Free WiFi'],
        ['Sheraton Tokyo Bay Hotel', 'Tokyo, Japan', 'The official hotel of Tokyo Disney Resort with spacious rooms and breathtaking ocean views.', 280.00, 4.5, 'https://images.unsplash.com/photo-1590490359683-658d3d23f972?auto=format&fit=crop&w=1200&q=80', 'Shuttle to Disney, Pool, Kids Club, Garden, 5 Restaurants'],
        ['Sheraton Grand Hotel Dubai', 'Dubai, UAE', 'A towering symbol of luxury on Sheikh Zayed Road, featuring a rooftop infinity pool and desert views.', 350.00, 4.9, 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80', 'Infinity Pool, Rooftop Bar, Luxury Spa, 24/7 Gym'],
        ['Sheraton Paris Airport Hotel', 'Paris, France', 'Elegance meets convenience at Charles de Gaulle Airport, perfect for international travelers.', 220.00, 4.4, 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1200&q=80', 'Soundproof Rooms, High-speed WiFi, Fine Dining, Lounge'],
        ['Sheraton Santorini Resort', 'Santorini, Greece', 'Stunning Cycladic architecture with Caldera views and private balconies for watching the sunset.', 500.00, 4.8, 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1200&q=80', 'Sea View, Breakfast Included, Spa, Terrace, Bar'],
        ['Sheraton Bali Kuta Resort', 'Bali, Indonesia', 'Located in the heart of Kuta, this resort offers a unique Balinese experience with modern amenities.', 180.00, 4.7, 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80', 'Beach Front, Infinity Pool, Kids Club, Spa'],
        ['Sheraton Grand Rio Hotel', 'Rio de Janeiro, Brazil', 'The only beachfront resort in Rio, nestled between the fashionable Leblon and Sao Conrado districts.', 260.00, 4.5, 'https://images.unsplash.com/photo-1483921020237-2ff51e8e4b22?auto=format&fit=crop&w=1200&q=80', 'Beach Access, Pool Bar, Tennis Courts, Gym']
    ];

    $stmt = $pdo->prepare("INSERT INTO hotels (name, location, description, price_per_night, rating, image_url, amenities) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($hotels as $hotel) {
        $stmt->execute($hotel);
        echo "Inserted: <strong>{$hotel[0]}</strong><br>";
    }

    echo "<h2 style='color:green;'>SUCCESS: All hotels seeded successfully!</h2>";
    echo "<a href='index.php'>Go to Homepage</a>";

} catch (PDOException $e) {
    echo "<h2 style='color:red;'>ERROR: " . $e->getMessage() . "</h2>";
}
?>
