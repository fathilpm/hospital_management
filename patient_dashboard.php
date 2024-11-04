<?php
include 'db.php'; 
session_start();

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: patient_login.php'); // Redirect to login if not logged in
    exit();
}

// Get patient information
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$_SESSION['patient_id']]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    echo "Patient not found.";
    exit();
}

// Get patient appointments
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ?");
$stmt->execute([$_SESSION['patient_id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get billing records
$stmt = $pdo->prepare("SELECT * FROM billing WHERE patient_name = ?");
$stmt->execute([htmlspecialchars($patient['name'])]);
$billing_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the payment has been made
if (isset($_SESSION['payment_success'])) {
    $payment_id = $_SESSION['payment_success'];
    // Find the corresponding billing record to update status
    foreach ($billing_records as &$billing) {
        if ($billing['id'] == $payment_id) {
            $billing['status'] = 'Paid'; // Update billing status to Paid
            break; // Exit loop after updating
        }
    }
    unset($_SESSION['payment_success']); // Clear the session variable
}

// Fetch departments
$stmt = $pdo->query("SELECT id, name FROM departments");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch doctors for the dropdown based on department
$doctors = [];
if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
    $stmt = $pdo->prepare("SELECT id, name FROM doctors WHERE department_id = ?");
    $stmt->execute([$department_id]);
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle appointment request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_appointment'])) {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['date'];
    $appointment_time = $_POST['time'];
    
    // Check for existing appointments to prevent duplicates
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND doctor_id = ? AND appointment_date = ? AND appointment_time = ?");
    $stmt->execute([$_SESSION['patient_id'], $doctor_id, $appointment_date, $appointment_time]);
    
    if ($stmt->rowCount() === 0) {
        // Insert appointment request into the database
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$_SESSION['patient_id'], $doctor_id, $appointment_date, $appointment_time]);
        
        echo "<p>Appointment request submitted successfully!</p>";
    } else {
        echo "<p>You have already requested an appointment for this date and time.</p>";
    }
}

// Handle payment action
if (isset($_GET['pay_bill'])) {
    // Simulate payment processing here
    $billing_id = $_GET['pay_bill'];
    
    // Update the payment status in the database
    $stmt = $pdo->prepare("UPDATE billing SET status = 'Paid' WHERE id = ?");
    $stmt->execute([$billing_id]);

    // Store the billing ID in session to show updated status on return
    $_SESSION['payment_success'] = $billing_id;

    // Redirect to payment success page
    header('Location: payment_success.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
    <script>
        function updateDoctors(departmentId) {
            const formData = new FormData();
            formData.append('department_id', departmentId);

            fetch('get_doctors.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const doctorSelect = document.getElementById('doctor_id');
                doctorSelect.innerHTML = '<option value="">Select a doctor</option>'; // Reset options
                data.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = doctor.name;
                    doctorSelect.appendChild(option);
                });
            });
        }
    </script>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($patient['name']) ?></h1>
    <h2>Your Information</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($patient['name']) ?></p>
    <p><strong>Age:</strong> <?= htmlspecialchars($patient['age']) ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($patient['phone']) ?></p>
    <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($patient['address'])) ?></p>

    <h2>Your Appointments</h2>
    <?php if (count($appointments) > 0): ?>
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Doctor ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['id']) ?></td>
                    <td><?= htmlspecialchars($appointment['doctor_id']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>

    <h2>Your Billing Records</h2>
    <?php if (count($billing_records) > 0): ?>
        <table>
            <tr>
                <th>Billing ID</th>
                <th>Patient Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th> <!-- New column for actions -->
            </tr>
            <?php foreach ($billing_records as $billing): ?>
                <tr>
                    <td><?= htmlspecialchars($billing['id']) ?></td>
                    <td><?= htmlspecialchars($billing['patient_name']) ?></td>
                    <td><?= htmlspecialchars($billing['amount']) ?></td>
                    <td><?= htmlspecialchars($billing['status']) ?></td>
                    <td><?= htmlspecialchars($billing['created_at']) ?></td>
                    <td>
                        <?php if ($billing['status'] === 'Unpaid'): ?>
                            <a href="?pay_bill=<?= htmlspecialchars($billing['id']) ?>" class="pay-button">Pay</a> <!-- Link to pay bill -->
                        <?php else: ?>
                            <span>Paid</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No billing records found.</p>
    <?php endif; ?>

    <h2>Request an Appointment</h2>
    <form method="POST">
        <label for="department_id">Select Department:</label>
        <select name="department_id" id="department_id" onchange="updateDoctors(this.value)" required>
            <option value="">Select a department</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= htmlspecialchars($department['id']) ?>"><?= htmlspecialchars($department['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="doctor_id">Select Doctor:</label>
        <select name="doctor_id" id="doctor_id" required>
            <option value="">Select a doctor</option>
            <?php foreach ($doctors as $doctor): ?>
                <option value="<?= htmlspecialchars($doctor['id']) ?>"><?= htmlspecialchars($doctor['name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <label for="date">Date:</label>
        <input type="date" name="date" required><br>
        
        <label for="time">Time:</label>
        <input type="time" name="time" required><br>
        
        <input type="submit" name="request_appointment" value="Request Appointment">
    </form>

    <h2>Actions</h2>
    <ul>
        <li><a href="view_prescriptions.php">View Prescriptions</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
