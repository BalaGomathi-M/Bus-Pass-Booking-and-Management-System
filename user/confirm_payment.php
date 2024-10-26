<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
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

// Get email from session
$email = $_SESSION['email'];

// Retrieve the transaction ID from the session
$transaction_id = $_SESSION['transaction_id'];

// Fetch payment details using transaction ID from QR code
if (isset($_POST['payment_details'])) {
    $payment_data = explode(';', $_POST['payment_details']);
    $payment_info = [];

    foreach ($payment_data as $data) {
        list($key, $value) = explode(':', $data);
        $payment_info[$key] = $value;
    }

    $passNumber = $payment_info['PassNumber'];
    $amount = $payment_info['Amount'];

    // Prepare SQL statement to insert payment details
    $payment_sql = "INSERT INTO tblpayment (PassNumber, Amount, PaymentStatus, TransactionID) VALUES (?, ?, 'Pending', ?)";
    $payment_stmt = $conn->prepare($payment_sql);
    $payment_stmt->bind_param("sds", $passNumber, $amount, $transaction_id);
    
    if ($payment_stmt->execute()) {
        echo "Payment information submitted successfully. Awaiting admin approval.";
        // Optionally, provide a link to download the pass (disabled until payment is confirmed)
        echo '<a href="download_pass.php?passnumber=' . urlencode($passNumber) . '" style="display:none;">Download your pass</a>';
    } else {
        echo "Error storing payment details: " . $conn->error;
    }

    $payment_stmt->close();
} else {
    echo "No payment details provided.";
}

$conn->close();
?>
