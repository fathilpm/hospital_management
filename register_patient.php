<?php include 'db.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Register as a Patient</h1>
    <form method="POST">
        Name: <input type="text" name="name" required><br>
        Password: <input type="password" name="password" required><br>
        Age: <input type="number" name="age"><br>
        Gender: 
        <select name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        Phone: <input type="text" name="phone"><br>
        Address: <textarea name="address"></textarea><br>
        <input type="submit" value="Register">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO patients (name, password, age, gender, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $passwordHash, $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['address']]);
        echo "Registration successful!";
    }
    ?>
</body>
</html>
