<?php include 'db.php'; session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch all patients for the dropdown
$stmt = $pdo->query("SELECT id, name FROM patients");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize prescriptions array
$prescriptions = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];

    // Fetch prescriptions for the selected patient
    $stmt = $pdo->prepare("SELECT p.*, d.name AS doctor_name FROM prescriptions p JOIN doctors d ON p.doctor_id = d.id WHERE p.patient_id = ?");
    $stmt->execute([$patient_id]);
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Prescriptions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>View Prescriptions</h1>
    
    <form method="POST">
        <label for="patient_id">Select Patient:</label>
        <select name="patient_id" id="patient_id" required>
            <option value="">Select a patient</option>
            <?php foreach ($patients as $patient): ?>
                <option value="<?= htmlspecialchars($patient['id']) ?>"><?= htmlspecialchars($patient['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="View Prescriptions">
    </form>

    <?php if (!empty($prescriptions)): ?>
        <h2>Prescriptions for <?= htmlspecialchars($patients[array_search($patient_id, array_column($patients, 'id'))]['name']) ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Instructions</th>
                <th>Date</th>
                <th>Doctor</th>
            </tr>
            <?php foreach ($prescriptions as $prescription): ?>
                <tr>
                    <td><?= htmlspecialchars($prescription['id']) ?></td>
                    <td><?= htmlspecialchars($prescription['medication']) ?></td>
                    <td><?= htmlspecialchars($prescription['dosage']) ?></td>
                    <td><?= nl2br(htmlspecialchars($prescription['instructions'])) ?></td>
                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($prescription['date']))) ?></td>
                    <td><?= htmlspecialchars($prescription['doctor_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>No prescriptions found for the selected patient.</p>
    <?php endif; ?>

    <h2>Actions</h2>
    <ul>
        <li><a href="admin_dashboard.php">Back to Admin Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
