-- Sheraton Hotel Clone Database Schema
-- Database: rsk2_20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create Database if permitted (Note: On shared hosting, create it via cPanel first)
-- CREATE DATABASE IF NOT EXISTS `rsk2_20`;
-- USE `rsk2_20`;

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `hotels`
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `bookings`
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data for `hotels`
INSERT INTO `hotels` (`name`, `location`, `description`, `price_per_night`, `rating`, `image_url`, `amenities`) VALUES
('Sheraton Grand Los Angeles', 'Los Angeles, CA', 'Experience the glamour of LA at our downtown hotel with stunning city views.', '250.00', '4.8', 'https://cache.marriott.com/content/dam/marriott-renditions/LAXSI/laxsi-exterior-0034-hor-wide.jpg?output-quality=70&interpolation=progressive-bilinear&downsize=1336px:*', 'Free WiFi, Pool, Gym, Spa'),
('Sheraton New York Times Square', 'New York, NY', 'Stay in the heart of NYC, steps away from Broadway and Central Park.', '320.00', '4.6', 'https://cache.marriott.com/content/dam/marriott-renditions/NYCSJ/nycsj-exterior-0056-hor-wide.jpg?output-quality=70&interpolation=progressive-bilinear&downsize=1336px:*', 'Free WiFi, Restaurant, Bar, Concierge'),
('Sheraton Maldives Full Moon Resort', 'Maldives', 'A tropical paradise with overwater bungalows and pristine white sands.', '650.00', '4.9', 'https://cache.marriott.com/content/dam/marriott-renditions/MLESI/mlesi-bungalow-0044-hor-wide.jpg?output-quality=70&interpolation=progressive-bilinear&downsize=1336px:*', 'Beach Access, Spa, Water Sports, All-inclusive'),
('Sheraton London Park Lane', 'London, UK', 'Art Deco elegance in the heart of Mayfair, overlooking Green Park.', '400.00', '4.7', 'https://cache.marriott.com/content/dam/marriott-renditions/LONPL/lonpl-exterior-0033-hor-wide.jpg?output-quality=70&interpolation=progressive-bilinear&downsize=1336px:*', 'Afternoon Tea, Gym, Meeting Rooms, Pet Friendly'),
('Sheraton Tokyo Bay Hotel', 'Tokyo, Japan', 'The official hotel of Tokyo Disney Resort with spacious rooms and ocean views.', '280.00', '4.5', 'https://cache.marriott.com/content/dam/marriott-renditions/TYOSI/tyosi-exterior-0055-hor-wide.jpg?output-quality=70&interpolation=progressive-bilinear&downsize=1336px:*', 'Shuttle to Disney, Pool, Kids Club, Garden');

COMMIT;
