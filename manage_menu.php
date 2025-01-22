<?php
session_start();
include 'db_connect.php';

// Ensure the user is a restaurant owner
if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'restaurant_owner') {
    header("Location: login.php");
    exit;
}

// Fetch the restaurant associated with the owner
$stmt = $conn->prepare("SELECT RestaurantID, Name FROM restaurants WHERE OwnerID = ?");
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$restaurantRes = $stmt->get_result();

if ($restaurantRes->num_rows < 1) {
    echo "<p>You do not own any restaurants.</p>";
    exit;
}

$restaurant = $restaurantRes->fetch_assoc();
$restaurantId = $restaurant['RestaurantID'];

// Handle form submission for adding a new menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $itemName = $_POST['ItemName'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    // Since images are removed, no need to handle ImageURL

    $stmt = $conn->prepare("INSERT INTO MenuItems (RestaurantID, ItemName, Description, Price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $restaurantId, $itemName, $description, $price);
    $stmt->execute();

    header("Location: manage_menu.php?id=" . $restaurantId);
    exit;
}

// Handle deletion of a menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $menuItemId = $_POST['MenuItemID'];

    $stmt = $conn->prepare("DELETE FROM MenuItems WHERE MenuItemID = ? AND RestaurantID = ?");
    $stmt->bind_param("ii", $menuItemId, $restaurantId);
    $stmt->execute();

    header("Location: manage_menu.php?id=" . $restaurantId);
    exit;
}

// Fetch all menu items for this restaurant
$stmt = $conn->prepare("SELECT * FROM MenuItems WHERE RestaurantID = ?");
$stmt->bind_param("i", $restaurantId);
$stmt->execute();
$menuRes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Menu - <?php echo htmlspecialchars($restaurant['Name']); ?> - MyFoodHub</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/manage_menu.css">
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

    <div class="manage-menu-container">
        <h1>Manage Menu - <?php echo htmlspecialchars($restaurant['Name']); ?></h1>

        <h2>Current Menu Items</h2>
        <table class="menu-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($menuRes->num_rows > 0) {
                    while ($item = $menuRes->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($item['ItemName']) . "</td>
                                <td>" . htmlspecialchars($item['Description']) . "</td>
                                <td>$" . number_format($item['Price'], 2) . "</td>
                                <td>
                                    <form action='manage_menu.php' method='POST'>
                                        <input type='hidden' name='MenuItemID' value='" . $item['MenuItemID'] . "'>
                                        <button type='submit' name='delete_item' class='btn-delete'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No menu items found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="add-item-form">
            <h3>Add New Menu Item</h3>
            <form action="manage_menu.php?id=<?php echo $restaurantId; ?>" method="POST">
                <input type="hidden" name="add_item" value="1">
                <div class="form-group">
                    <label for="ItemName">Item Name:</label>
                    <input type="text" id="ItemName" name="ItemName" required>
                </div>
                <div class="form-group">
                    <label for="Description">Description:</label>
                    <textarea id="Description" name="Description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="Price">Price ($):</label>
                    <input type="number" id="Price" name="Price" step="0.01" required>
                </div>
                <button type="submit" class="btn-add-item">Add Menu Item</button>
            </form>
        </div>
    </div>

    <footer class="main-footer">
        <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
    </footer>
</body>
</html>
