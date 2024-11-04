<?php 
session_start();

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: patient_login.php'); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Your payment has been processed successfully!</p>
    <p><a href="patient_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
