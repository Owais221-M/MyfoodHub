<?php
session_start();
include 'db_connect.php';

// Get restaurant ID from query string
$restaurantId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch restaurant info
$stmt = $conn->prepare("SELECT * FROM restaurants WHERE RestaurantID = ?");
$stmt->bind_param("i", $restaurantId);
$stmt->execute();
$restaurantRes = $stmt->get_result();

if ($restaurantRes->num_rows < 1) {
    echo "<p>Restaurant not found.</p>";
    exit;
}
$restaurant = $restaurantRes->fetch_assoc();

// Fetch menu items for this restaurant
$stmt2 = $conn->prepare("SELECT * FROM MenuItems WHERE RestaurantID = ?");
$stmt2->bind_param("i", $restaurantId);
$stmt2->execute();
$menuRes = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($restaurant['Name']); ?> - MyFoodHub</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/restaurant.css">
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

    <div class="restaurant-details">
        <div class="restaurant-header">
            <div class="restaurant-info">
                <h1><?php echo htmlspecialchars($restaurant['Name']); ?></h1>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($restaurant['Location']); ?></p>
                <p><strong>Cuisine:</strong> <?php echo htmlspecialchars($restaurant['CuisineType']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($restaurant['Description'])); ?></p>
            </div>
        </div>

        <div class="menu-section">
            <h2>Menu</h2>
            <div class="menu-items">
                <?php
                if ($menuRes->num_rows > 0) {
                    while ($item = $menuRes->fetch_assoc()) {
                        echo "<div class='menu-item'>
                                <div class='menu-info'>
                                    <h3>" . htmlspecialchars($item['ItemName']) . "</h3>
                                    <p>" . htmlspecialchars($item['Description']) . "</p>
                                    <p class='price'>$" . number_format($item['Price'], 2) . "</p>
                                    " . (isset($_SESSION['UserID']) && $_SESSION['role'] === 'customer' ? "
                                    <form action='cart.php' method='POST'>
                                        <input type='hidden' name='RestaurantID' value='" . $restaurantId . "'>
                                        <input type='hidden' name='MenuItemID' value='" . $item['MenuItemID'] . "'>
                                        <button type='submit' class='btn-add'>Add to Cart</button>
                                    </form>" : "<p><i>Login as a customer to order.</i></p>") . "
                                </div>
                              </div>";
                    }
                } else {
                    echo "<p>No menu items found for this restaurant.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
    </footer>
</body>
</html>
