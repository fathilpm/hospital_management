<?php include 'db.php'; session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all patients
$patients = $pdo->query("SELECT id, name FROM patients")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all doctors
$doctors = $pdo->query("SELECT id, name FROM doctors")->fetchAll(PDO::FETCH_ASSOC);

// Handle appointment booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Insert appointment into the database
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'Pending')");
    if ($stmt->execute([$patient_id, $doctor_id, $appointment_date, $appointment_time])) {
        echo "<p>Appointment booked successfully!</p>";
    } else {
        echo "<p>Error booking appointment.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Book Appointment</h1>
    <form method="POST">
        <label for="patient_id">Select Patient:</label>
        <select name="patient_id" required>
            <option value="">Select a patient</option>
            <?php foreach ($patients as $patient): ?>
                <option value="<?= htmlspecialchars($patient['id']) ?>"><?= htmlspecialchars($patient['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="doctor_id">Select Doctor:</label>
        <select name="doctor_id" required>
            <option value="">Select a doctor</option>
            <?php foreach ($doctors as $doctor): ?>
                <option value="<?= htmlspecialchars($doctor['id']) ?>"><?= htmlspecialchars($doctor['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="appointment_date">Appointment Date:</label>
        <input type="date" name="appointment_date" required><br>

        <label for="appointment_time">Appointment Time:</label>
        <input type="time" name="appointment_time" required><br>

        <input type="submit" value="Book Appointment">
    </form>

    <h2>Actions</h2>
    <ul>
        <li><a href="admin_dashboard.php">Back to Admin Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
