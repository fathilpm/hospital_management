<?php include 'db.php'; session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Billing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Billing Records</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Patient ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM billing");
        while ($billing = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $billing['id'] ?></td>
                <td><?= $billing['patient_id'] ?></td>
                <td><?= $billing['amount'] ?></td>
                <td><?= $billing['date'] ?></td>
                <td><?= $billing['status'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
