<?php
session_start();
include 'db_connect.php';

// 1) Handle "Add to Cart" submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MenuItemID'])) {
    $menuItemID   = intval($_POST['MenuItemID']);
    $restaurantID = intval($_POST['RestaurantID']);

    // If cart is empty or from a different restaurant, reset it
    if (!isset($_SESSION['cart']) || $_SESSION['cart']['RestaurantID'] != $restaurantID) {
        $_SESSION['cart'] = [
            'RestaurantID' => $restaurantID,
            'Items' => []
        ];
    }

    // Check if the item already exists in the cart
    $found = false;
    foreach ($_SESSION['cart']['Items'] as &$itm) {
        if ($itm['MenuItemID'] == $menuItemID) {
            $itm['Quantity'] += 1;
            $found = true;
            break;
        }
    }

    // If not found, fetch details from DB and add
    if (!$found) {
        $stmt = $conn->prepare("SELECT ItemName, Price FROM MenuItems WHERE MenuItemID = ?");
        $stmt->bind_param("i", $menuItemID);
        $stmt->execute();
        $res  = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $_SESSION['cart']['Items'][] = [
                'MenuItemID' => $menuItemID,
                'ItemName'   => $row['ItemName'],
                'Price'      => $row['Price'],
                'Quantity'   => 1
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

// 2) If updating quantity or removing items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantity
    if (isset($_POST['update_qty'], $_POST['new_qty'], $_POST['MenuItemID'])) {
        $menuItemID = intval($_POST['MenuItemID']);
        $newQty     = max(1, intval($_POST['new_qty'])); // ensure min 1
        foreach ($_SESSION['cart']['Items'] as &$itm) {
            if ($itm['MenuItemID'] == $menuItemID) {
                $itm['Quantity'] = $newQty;
                break;
            }
        }
    }
    // Remove item
    if (isset($_POST['remove'], $_POST['MenuItemID'])) {
        $menuItemID = intval($_POST['MenuItemID']);
        foreach ($_SESSION['cart']['Items'] as $index => $itm) {
            if ($itm['MenuItemID'] == $menuItemID) {
                unset($_SESSION['cart']['Items'][$index]);
                $_SESSION['cart']['Items'] = array_values($_SESSION['cart']['Items']);
                break;
            }
        }
    }
}

// 3) Display the cart
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - MyFoodHub</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/cart.css">
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

    <div class="cart-container">
        <h1>Your Cart</h1>
        <?php
        if (empty($_SESSION['cart']) || empty($_SESSION['cart']['Items'])) {
            echo "<p class='empty-cart'>Your cart is empty.</p>";
            exit;
        }

        // We have items in the cart
        $items = $_SESSION['cart']['Items'];
        $total = 0;
        ?>
        <table class="cart-table">
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($items as $itm): 
                $subtotal = $itm['Price'] * $itm['Quantity'];
                $total   += $subtotal; 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($itm['ItemName']); ?></td>
                <td>$<?php echo number_format($itm['Price'], 2); ?></td>
                <td>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="MenuItemID" value="<?php echo $itm['MenuItemID']; ?>">
                        <input type="number" name="new_qty" value="<?php echo $itm['Quantity']; ?>" min="1">
                        <button type="submit" name="update_qty" class="btn-update">Update</button>
                    </form>
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <form action="cart.php" method="POST" onsubmit="return confirm('Are you sure you want to remove this item?');">
                        <input type="hidden" name="MenuItemID" value="<?php echo $itm['MenuItemID']; ?>">
                        <button type="submit" name="remove" class="btn-remove">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </table>
        
        <!-- Checkout button -->
        <form action="place_order.php" method="POST">
            <button type="submit" class="checkout-btn">Checkout</button>
        </form>
    </div>

    <footer class="main-footer">
        <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
    </footer>
</body>
</html>
