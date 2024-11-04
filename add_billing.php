<?php include 'db.php'; session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle billing submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['patient_name']; // Assuming patient name is being submitted
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    
    // Fetch the patient ID using the patient name
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE name = ?");
    $stmt->execute([$patient_name]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patient) {
        $patient_id = $patient['id'];

        // Insert billing record into the database
        $stmt = $pdo->prepare("INSERT INTO billing (patient_id, patient_name, amount, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $patient_name, $amount, $status]);
        echo "Billing record added successfully!";
    } else {
        echo "Patient not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Billing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Billing Record</h1>
    <form method="POST">
        Patient Name: <input type="text" name="patient_name" required><br>
        Amount: <input type="number" step="0.01" name="amount" required><br>
        Status: 
        <select name="status">
            <option value="Paid">Paid</option>
            <option value="Pending">Pending</option>
            <option value="Cancelled">Cancelled</option>
        </select><br>
        <input type="submit" value="Add Billing">
    </form>
</body>
</html>
