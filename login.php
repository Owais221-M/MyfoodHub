<?php
session_start();
include 'db_connect.php';

// Initialize variables
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        // Fetch user from the database
        $stmt = $conn->prepare("SELECT UserID, Password, role, Username FROM users WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userID, $hashedPassword, $role, $dbUsername);
            $stmt->fetch();
            
            if (password_verify($password, $hashedPassword)) {
                // Credentials are correct, set session variables
                $_SESSION['UserID']   = $userID;
                $_SESSION['role']     = $role;
                $_SESSION['Username'] = $dbUsername;
                
                // Redirect to home page or dashboard
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MyFoodHub</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/login.css">
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

    <div class="login-container">
        <h2>Login to Your Account</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" class="login-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Login</button>
        </form>
        <p style="text-align:center; margin-top:1rem;">Don't have an account? <a href="register.php">Sign up here</a>.</p>
    </div>

    <footer class="main-footer">
        <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
    </footer>
</body>
</html>
