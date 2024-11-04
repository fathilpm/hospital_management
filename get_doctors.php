<?php
include 'db.php'; // Make sure to include your database connection

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
    
    // Fetch doctors based on department_id
    $stmt = $pdo->prepare("SELECT id, name FROM doctors WHERE department_id = ?");
    $stmt->execute([$department_id]);
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return doctors as JSON
    echo json_encode($doctors);
}
?>
