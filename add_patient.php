<?php include 'db.php'; session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Patient</h1>
    <form method="POST">
        Name: <input type="text" name="name" required><br>
        Age: <input type="number" name="age"><br>
        Gender: 
        <select name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        Phone: <input type="text" name="phone"><br>
        Address: <textarea name="address"></textarea><br>
        <input type="submit" value="Add Patient">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO patients (name, age, gender, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['address']]);
        echo "Patient added successfully!";
    }
    ?>
</body>
</html>
