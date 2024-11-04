<?php include 'db.php'; session_start();

// Check if admin or doctor is logged in
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['doctor_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if appointment_id is provided
if (!isset($_GET['appointment_id'])) {
    echo "No appointment specified.";
    exit();
}

// Fetch appointment details including patient info
$appointment_id = $_GET['appointment_id'];
$stmt = $pdo->prepare("SELECT a.patient_id, p.name FROM appointments a JOIN patients p ON a.patient_id = p.id WHERE a.id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    echo "Appointment not found.";
    exit();
}

// Fetch doctor ID from session
$doctor_id = isset($_SESSION['doctor_id']) ? $_SESSION['doctor_id'] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Prescription</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Prescription</h1>
    <form method="POST">
        Patient ID: <input type="number" name="patient_id" value="<?= htmlspecialchars($appointment['patient_id']) ?>" readonly><br>
        Patient Name: <input type="text" value="<?= htmlspecialchars($appointment['name']) ?>" readonly><br>
        Doctor ID: <input type="number" name="doctor_id" value="<?= htmlspecialchars($doctor_id) ?>" readonly><br>

        Medication: <input type="text" name="medication" required><br>
        Dosage: <input type="text" name="dosage" required><br>
        Instructions: <textarea name="instructions"></textarea><br>
        <input type="submit" value="Add Prescription">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO prescriptions (patient_id, doctor_id, medication, dosage, instructions) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['patient_id'],
            $_POST['doctor_id'],
            $_POST['medication'],
            $_POST['dosage'],
            $_POST['instructions']
        ]);
        
        // Redirect based on user type
        if (isset($_SESSION['doctor_id'])) {
            header('Location: doctor_dashboard.php');
        } else {
            header('Location: admin_dashboard.php');
        }
        exit(); // Ensure no further code is executed after redirection
    }
    ?>
</body>
</html>
