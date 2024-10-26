<?php
session_start(); // Start session

// Database connection
$con = mysqli_connect("localhost", "root", "", "buspassdb");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    die("User is not logged in. Please log in to make the payment.");
}

// Get the user's email from the session
$email = $_SESSION['email'];

// Get POST data
$pass_id = $_POST['pass_id'];
$amount = $_POST['amount'];

// Fetch the user's pass details to get the UPI ID
$passQuery = $con->prepare("SELECT * FROM tblpass WHERE ID = ? AND Email = ?");
$passQuery->bind_param("is", $pass_id, $email);
$passQuery->execute();
$passResult = $passQuery->get_result();

if ($passResult && $passResult->num_rows > 0) {
    $passRow = $passResult->fetch_assoc();
    $passNumber = $passRow['PassNumber'];

    // Define the UPI ID directly
    $upi_id = "9965857942@okicici"; // Replace with your actual UPI ID

    // Construct the UPI payment link
    $transaction_note = "Payment for Pass Number: " . $passNumber;
    $upi_link = "upi://pay?pa=" . urlencode($upi_id) . 
                "&am=" . urlencode($amount) . 
                "&tn=" . urlencode($transaction_note);

    // Redirect to the UPI payment link
    header("Location: " . $upi_link);
    exit();
} else {
    die("Invalid pass ID or user not authorized to make this payment.");
}

// Close the database connection
mysqli_close($con);
?>
