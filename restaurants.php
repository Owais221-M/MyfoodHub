<?php
session_start();
include 'db_connect.php';

// Fetch all restaurants
$restaurantsQuery = "SELECT RestaurantID, Name, Location, CuisineType, ImageURL FROM restaurants";
$restaurantsResult = $conn->query($restaurantsQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurants - MyFoodHub</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/restaurants.css">
</head>
<body>

<header class="main-header">
    <div class="logo">
        <a href="index.php">MyFood<span>Hub</span></a>
    </div>
    <nav class="nav-links">
        <ul>
            <li><a href="restaurants.php">Restaurants</a></li>
            <?php if (isset($_SESSION['UserID'])): ?>
                <?php if ($_SESSION['role'] === 'restaurant_owner'): ?>
                    <li><a href="manage_menu.php">My Restaurant</a></li>
                <?php else: ?>
                    <li><a href="cart.php">My Cart</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="restaurants-container">
    <h2>All Restaurants</h2>
    <div class="restaurant-list">
        <?php
        if ($restaurantsResult->num_rows > 0) {
            while ($row = $restaurantsResult->fetch_assoc()) {
                $imageURL = htmlspecialchars($row['ImageURL'] ?: 'https://via.placeholder.com/300x200?text=No+Image');
                echo "<div class='restaurant-item'>
                        <img src='" . $imageURL . "' alt='" . htmlspecialchars($row['Name']) . "'>
                        <div class='restaurant-info'>
                            <h3>" . htmlspecialchars($row['Name']) . "</h3>
                            <p>" . htmlspecialchars($row['CuisineType']) . " | " . htmlspecialchars($row['Location']) . "</p>
                            <a href='restaurant.php?id=" . $row['RestaurantID'] . "' class='btn-view'>View Menu</a>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No restaurants found.</p>";
        }
        ?>
    </div>
</div>

<footer class="main-footer">
    <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
</footer>

</body>
</html>
