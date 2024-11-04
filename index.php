<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h2>Hospital Management System</h2>
        <nav>
            <a href="admin_login.php">Admin</a>
            <a href="doctor_login.php">Doctor</a>
            <a href="patient_login.php">Patient</a>
        </nav>
    </header>

    <div class="container">
        <div class="card">
            <img src="doctor.jpg" alt="Doctor Login">
            <p>We are employing for doctors. Tap here to join us</p>
            <a href="doctor_login.php" class="button">Apply Now</a>
        </div>
        <div class="card">
            <img src="patient.jpg" alt="Patient Login">
            <p>Create an account so that we can take care of you.</p>
            <a href="patient_login.php" class="button">Create Account</a>
        </div>
        <div class="card">
            <img src="info.jpg" alt="More Info">
            <p>Click on the button for more information.</p>
            <a href="info.php" class="button">More Info</a>
        </div>
    </div>
</body>
</html>
