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

// Prepare the SQL statement to fetch the pass details for the logged-in user
$sql = "SELECT PassNumber, Amount FROM tblpass WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the pass details
    $pass = $result->fetch_assoc();
} else {
    echo "No active pass found.";
    exit();
}

$stmt->close();

// Generate a unique transaction ID
$transaction_id = "TXN" . uniqid();

// Store transaction ID in session
$_SESSION['transaction_id'] = $transaction_id;

// Create a string with all payment details
$payment_details = "PassNumber:{$pass['PassNumber']};Amount:{$pass['Amount']};TransactionID:{$transaction_id}";

// Path to store the QR code image
$qr_image_path = "upi_qr_code.png";

// Generate the QR code for the payment details
require_once('../phpqrcode-master/qrlib.php'); // Include the QR code generation library
QRcode::png($payment_details, $qr_image_path, QR_ECLEVEL_L, 10);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay for Bus Pass</title>
</head>
<body>
    <h1>Pay for Bus Pass</h1>
    
    <!-- Display pass details -->
    <p>Pass Number: <?php echo $pass['PassNumber']; ?></p>
    <p>Amount: ₹<?php echo $pass['Amount']; ?></p>

    <!-- Display the pre-generated QR code -->
    <p>Scan the QR code below to pay using Google Pay or any UPI app:</p>
    <img src="<?php echo $qr_image_path; ?>" alt="UPI Payment QR Code" style="width: 200px; height: 200px;">

    <p>Open your UPI app, scan the QR code, and complete the payment.</p>
    <p>After payment, please confirm it below:</p>
    <form action="confirm_payment.php" method="post">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>
