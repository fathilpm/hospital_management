<?php include 'db.php'; session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Doctors</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>List of Doctors</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Phone</th>
        </tr>
        <?php
        // Fetch doctors along with department names
        $stmt = $pdo->prepare("
            SELECT d.id, d.name, dep.name AS department, d.phone
            FROM doctors d
            LEFT JOIN departments dep ON d.department_id = dep.id
        ");
        $stmt->execute();
        
        while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($doctor['id']) ?></td>
                <td><?= htmlspecialchars($doctor['name']) ?></td>
                <td><?= isset($doctor['department']) ? htmlspecialchars($doctor['department']) : 'N/A' ?></td>
                <td><?= htmlspecialchars($doctor['phone']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
