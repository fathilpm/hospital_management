<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php'); // Redirect to admin dashboard if already logged in
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = $username; // Store admin username in session
        header('Location: admin_dashboard.php'); // Redirect to admin dashboard
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
</head>
<body>
    <h1>Admin Login</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <p>Not an admin? <a href="index.php">Go to Home</a></p>
</body>
</html>
