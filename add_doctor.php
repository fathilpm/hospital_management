<?php include 'db.php'; session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch departments for the dropdown
$stmt = $pdo->query("SELECT id, name FROM departments");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Doctor</h1>
    <form method="POST">
        Name: <input type="text" name="name" required><br>
        Department: 
        <select name="department_id" required>
            <option value="">Select a department</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= htmlspecialchars($department['id']) ?>"><?= htmlspecialchars($department['name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        Phone: <input type="text" name="phone"><br>
        Password: <input type="password" name="password" required><br> <!-- New Password Field -->
        <input type="submit" value="Add Doctor">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO doctors (name, password, department_id, phone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $hashedPassword, $_POST['department_id'], $_POST['phone']]);
        echo "Doctor added successfully!";
    }
    ?>
</body>
</html>
