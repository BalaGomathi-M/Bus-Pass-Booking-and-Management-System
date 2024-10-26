<?php
session_start();
require 'vendor/autoload.php'; // Include Composer's autoloader
include('includes/dbconnection.php'); // Include your database connection

// Instamojo API credentials
$api_key = 'your_api_key';
$auth_token = 'your_auth_token';
$api_url = 'https://test.instamojo.com/api/1.1/'; // Use live URL for production

// Check if a session exists and retrieve the pass number
if (!isset($_SESSION['PassNumber'])) {
    die("Pass Number not found in session.");
}

$passNumber = $_SESSION['PassNumber'];

// Query to retrieve the user details from tblpass
$sql = "SELECT FullName, Email, ContactNumber, amount FROM tblpass WHERE PassNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $passNumber);
$stmt->execute();
$result = $stmt->get_result();

// Check if pass details are found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['FullName'];
    $email = $row['Email'];
    $phone = $row['ContactNumber'];
    $amount = $row['amount']; // Amount is retrieved from tblpass
} else {
    die("No pass found with this PassNumber.");
}

// Initialize the Instamojo API client
$api = new Instamojo\Instamojo($api_key, $auth_token, $api_url);

try {
    // Create a new payment request using the retrieved details
    $response = $api->paymentRequestCreate([
        "purpose" => "Bus Pass Renewal",
        "amount" => $amount,
        "buyer_name" => $name,
        "email" => $email,
        "phone" => $phone,
        "redirect_url" => "http://yourdomain.com/payment_success.php",
        "send_email" => true,
        "send_sms" => true,
        "allow_repeated_payments" => false
    ]);

    // Store the transaction ID in the session for reference after payment
    $_SESSION['TransactionID'] = $response['id'];

    // Redirect the user to the Instamojo payment page
    header("Location: " . $response['longurl']);
    exit();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
