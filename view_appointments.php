<?php include 'db.php'; session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle appointment approval or decline
if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];

    // Update appointment status to 'Confirmed'
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Confirmed' WHERE id = ?");
    $stmt->execute([$approve_id]);

    echo "<p>Appointment ID $approve_id has been confirmed.</p>";
} elseif (isset($_GET['decline_id'])) {
    $decline_id = $_GET['decline_id'];

    // Update appointment status to 'Declined'
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Declined' WHERE id = ?");
    $stmt->execute([$decline_id]);

    echo "<p>Appointment ID $decline_id has been declined.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>List of Appointments</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Patient ID</th>
            <th>Doctor ID</th>
            <th>Appointment Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM appointments");
        while ($appointment = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['id']) ?></td>
                <td><?= htmlspecialchars($appointment['patient_id']) ?></td>
                <td><?= htmlspecialchars($appointment['doctor_id']) ?></td>
                <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                <td><?= htmlspecialchars($appointment['status']) ?></td>
                <td>
                    <?php if ($appointment['status'] === 'Pending'): ?>
                        <a href="?approve_id=<?= htmlspecialchars($appointment['id']) ?>">Approve</a>
                        <a href="?decline_id=<?= htmlspecialchars($appointment['id']) ?>">Decline</a>
                    <?php else: ?>
                        <span><?= htmlspecialchars($appointment['status']) ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
