<?php include 'db.php'; session_start();

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: patient_login.php'); // Redirect to login if not logged in
    exit();
}

// Get patient prescriptions
$stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE patient_id = ?");
$stmt->execute([$_SESSION['patient_id']]);
$prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Prescriptions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Your Prescriptions</h1>
    <?php if (count($prescriptions) > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Doctor ID</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Instructions</th>
                <th>Date</th>
            </tr>
            <?php foreach ($prescriptions as $prescription): ?>
                <tr>
                    <td><?= htmlspecialchars($prescription['id']) ?></td>
                    <td><?= htmlspecialchars($prescription['doctor_id']) ?></td>
                    <td><?= htmlspecialchars($prescription['medication']) ?></td>
                    <td><?= htmlspecialchars($prescription['dosage']) ?></td>
                    <td><?= nl2br(htmlspecialchars($prescription['instructions'])) ?></td>
                    <td><?= htmlspecialchars($prescription['date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No prescriptions found.</p>
    <?php endif; ?>
    
    <p><a href="patient_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
