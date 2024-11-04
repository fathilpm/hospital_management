<?php 
include 'db.php'; 
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Patient Login</h1>
    
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        
        <input type="submit" value="Login">
    </form>

    <p>Not a patient? <a href="register_patient.php">Register now</a></p>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE name = ?");
        $stmt->execute([$_POST['name']]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient && password_verify($_POST['password'], $patient['password'])) {
            $_SESSION['patient_id'] = $patient['id'];
            $_SESSION['patient_name'] = $patient['name']; // Store patient name in session
            header('Location: patient_dashboard.php'); // Redirect to patient's dashboard
            exit();
        } else {
            echo "<p style='color:red;'>Invalid name or password.</p>"; // Display error in red
        }
    }
    ?>
</body>
</html>
