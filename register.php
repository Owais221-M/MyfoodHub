<?php
session_start();
include 'db_connect.php';

// Initialize variables
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'customer';

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT UserID FROM users WHERE Username = ? OR Email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            // Hash password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (Username, Email, Password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
            
            if ($stmt->execute()) {
                // Registration successful, log the user in
                $_SESSION['UserID'] = $stmt->insert_id;
                $_SESSION['role']   = $role;
                $_SESSION['Username'] = $username;
                
                // Redirect to home page or dashboard
                header("Location: index.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
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
    <title>Register - MyFoodHub</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/register.css">
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

    <div class="form-container">
        <h2>Create Your Account</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Register As:</label>
                <select id="role" name="role" required>
                    <option value="customer" <?php if (($role ?? '') === 'customer') echo 'selected'; ?>>Customer</option>
                    <option value="restaurant_owner" <?php if (($role ?? '') === 'restaurant_owner') echo 'selected'; ?>>Restaurant Owner</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Sign Up</button>
        </form>
        <p style="text-align:center; margin-top:1rem;">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>

    <footer class="main-footer">
        <p>&copy; 2025 MyFoodHub. All rights reserved.</p>
    </footer>
</body>
</html>
