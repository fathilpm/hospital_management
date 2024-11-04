<?php include 'db.php'; session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Patients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>List of Patients</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM patients");
        while ($patient = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $patient['id'] ?></td>
                <td><?= $patient['name'] ?></td>
                <td><?= $patient['age'] ?></td>
                <td><?= $patient['gender'] ?></td>
                <td><?= $patient['phone'] ?></td>
                <td><?= $patient['address'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
