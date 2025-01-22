<?php
// Start the session and include the database connection
session_start();
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home - MyFoodHub</title>
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/index.css">
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

<section class="hero">
  <div class="hero-content">
    <h1>Delicious Food, Delivered Fast</h1>
    <p>Explore top restaurants in your city and order your favorites!</p>
    <a href="restaurants.php" class="btn-hero">Order Now</a>
  </div>
</section>

<section class="featured-restaurants">
  <h2>Featured Restaurants</h2>
  <div class="restaurant-cards">

    <?php
    $featuredQuery = "SELECT RestaurantID, Name, Location, CuisineType, ImageURL FROM restaurants LIMIT 3";
    $featuredResult = $conn->query($featuredQuery);

    if ($featuredResult === false) {
        echo "<p>Error fetching featured restaurants: " . htmlspecialchars($conn->error) . "</p>";
    } elseif ($featuredResult->num_rows > 0) {
        while ($row = $featuredResult->fetch_assoc()) {
            // Use the ImageURL directly from the database
            $imageURL = $row['ImageURL'];

            echo '<div class="card">
                    <img src="' . htmlspecialchars($imageURL) . '" alt="' . htmlspecialchars($row['Name']) . '">
                    <h3>' . htmlspecialchars($row['Name']) . '</h3>
                    <p>' . htmlspecialchars($row['CuisineType']) . ' - ' . htmlspecialchars($row['Location']) . '</p>
                    <a href="restaurant.php?id=' . urlencode($row['RestaurantID']) . '" class="btn-view">View Menu</a>
                  </div>';
        }
    } else {
        echo "<p>No featured restaurants available.</p>";
    }
    ?>

  </div>
</section>

<footer class="main-footer">
  <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
</footer>

</body>
</html>
