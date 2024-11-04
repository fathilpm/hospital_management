<?php include 'db.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Doctor Login</h1>
    <form method="POST">
        Name: <input type="text" name="name" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE name = ?");
        $stmt->execute([$_POST['name']]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor && password_verify($_POST['password'], $doctor['password'])) {
            $_SESSION['doctor_id'] = $doctor['id'];
            header('Location: doctor_dashboard.php');
            exit();
        } else {
            echo "Invalid name or password.";
        }
    }
    ?>
</body>
</html>
