<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection details
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "buspassdb";

// Create a new database connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check for any connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get payment details from POST
$payment_id = $_POST['payment_id'];
$pass_number = $_POST['pass_number'];

// Update payment status
$sql = "UPDATE tblpayment SET PaymentStatus = 'Paid' WHERE PaymentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);

if ($stmt->execute()) {
    echo "Payment status updated successfully.";
    // Optionally, provide feedback
    echo '<p>Payment marked as paid. The user can now download their pass.</p>';
    echo '<a href="download_pass.php?passnumber=' . urlencode($pass_number) . '">Download your pass</a>';
} else {
    echo "Error updating payment status: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
