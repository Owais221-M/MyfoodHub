<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'customer') {
    echo "<p>You must be logged in as a customer to place an order.</p>";
    exit;
}

if (empty($_SESSION['cart']) || empty($_SESSION['cart']['Items'])) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

$userID       = $_SESSION['UserID'];
$restaurantID = $_SESSION['cart']['RestaurantID'];
$orderDate    = date('Y-m-d H:i:s');

// 1) Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (UserID, RestaurantID, OrderDate, OrderStatus) 
                        VALUES (?, ?, ?, 'Pending')");
$stmt->bind_param("iis", $userID, $restaurantID, $orderDate);
$stmt->execute();
$orderID = $stmt->insert_id;

// 2) Insert each item into order_items
foreach ($_SESSION['cart']['Items'] as $itm) {
    $menuItemID = $itm['MenuItemID'];
    $quantity   = $itm['Quantity'];
    $price      = $itm['Price'];

    $stmt2 = $conn->prepare("INSERT INTO order_items (OrderID, MenuItemID, Quantity, Price)
                             VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $orderID, $menuItemID, $quantity, $price);
    $stmt2->execute();
}

// 3) Clear the cart
$_SESSION['cart'] = [];

// 4) Confirmation
echo "<h1>Order Placed!</h1>";
echo "<p>Your order #$orderID has been placed successfully.</p>";
echo "<p><a href='index.php'>Back to Home</a></p>";
