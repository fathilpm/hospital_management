<?php include 'db.php'; session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: doctor_login.php'); // Redirect to login if not logged in
    exit();
}

// Get doctor information
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$_SESSION['doctor_id']]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    echo "Doctor not found.";
    exit();
}

// Get appointments for this doctor, showing Pending and Confirmed appointments
$stmt = $pdo->prepare("
    SELECT a.*, p.medication, p.dosage, p.instructions 
    FROM appointments a 
    LEFT JOIN prescriptions p ON a.id = p.appointment_id 
    WHERE a.doctor_id = ? AND (a.status = 'Pending' OR a.status = 'Confirmed')
");
$stmt->execute([$doctor['id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle appointment approval or decline
if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Confirmed' WHERE id = ?");
    if ($stmt->execute([$approve_id])) {
        header("Location: doctor_dashboard.php"); // Redirect after approval
        exit();
    } else {
        echo "<p>Error updating appointment status to confirmed.</p>";
    }
} elseif (isset($_GET['decline_id'])) {
    $decline_id = $_GET['decline_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Declined' WHERE id = ?");
    if ($stmt->execute([$decline_id])) {
        header("Location: doctor_dashboard.php"); // Redirect after decline
        exit();
    } else {
        echo "<p>Error updating appointment status to declined.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($doctor['name']) ?></h1>

    <h2>Your Appointments</h2>
    <?php if (count($appointments) > 0): ?>
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Patient ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Prescription</th>
                <th>Action</th>
            </tr>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['id']) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_id']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                    <td>
                        <?php if ($appointment['medication']): ?>
                            <span>Done</span>
                            <a href="edit_prescription.php?appointment_id=<?= htmlspecialchars($appointment['id']) ?>">Edit</a>
                        <?php elseif ($appointment['status'] === 'Confirmed'): ?>
                            <a href="add_prescription.php?appointment_id=<?= htmlspecialchars($appointment['id']) ?>">Add</a>
                        <?php else: ?>
                            <span>N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($appointment['status'] === 'Pending'): ?>
                            <a href="?approve_id=<?= htmlspecialchars($appointment['id']) ?>">Approve</a>
                            <a href="?decline_id=<?= htmlspecialchars($appointment['id']) ?>">Decline</a>
                        <?php else: ?>
                            <span><?= htmlspecialchars($appointment['status']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>

    <h2>Actions</h2>
    <ul>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
