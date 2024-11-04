<?php include 'db.php'; session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome to the Admin Dashboard</h1>
    <a href="add_patient.php">Add Patient</a><br>
    <a href="view_patients.php">Manage Patients</a><br>
    <a href="add_doctor.php">Add Doctor</a><br>
    <a href="view_doctor.php">Manage Doctors</a><br>
    <a href="book_appointments.php">Book Appointment</a><br>
    <a href="view_appointments.php">View Appointments</a><br>
    <a href="add_billing.php">Add Billing</a><br>
    <a href="view_billing.php">View Billing</a><br>
    <a href="view_prescriptions_admin.php">View Prescription</a><br>
    <a href="logout.php">Logout</a><br>
</body>
</html>
