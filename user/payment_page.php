<?php
session_start();
include('includes/dbconnection.php');

// Retrieve PassNumber from session
if (!isset($_SESSION['PassNumber'])) {
    die("Pass Number not found in session.");
}

$passNumber = $_SESSION['PassNumber'];

// Fetch details from tblpass
$sql = "SELECT FullName, Email, ContactNumber, amount FROM tblpass WHERE PassNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $passNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['FullName'];
    $email = $row['Email'];
    $phone = $row['ContactNumber'];
    $amount = $row['amount'];
} else {
    die("No pass found with this PassNumber.");
}

// Set fixed conversion rate from INR to USD
$conversionRate = 82; // 1 USD = 82 INR
$amountInUSD = $amount / $conversionRate; // Convert amount to USD
$amountInUSD = number_format($amountInUSD, 2); // Format to 2 decimal places
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Payment</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #007bff;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">Bus Pass Payment</div>
            <div class="card-body">
                <h3>Pass Accepted</h3>
                <p><strong>Pass Number:</strong> <?php echo $passNumber; ?></p>
                <p><strong>Amount to be Paid:</strong> ₹<?php echo $amount; ?> (Approx. $<?php echo $amountInUSD; ?> USD)</p>

                <!-- PayPal.me button to redirect to payment with USD as the currency -->
                <a href="https://www.paypal.me/BalaGomathiM/<?php echo $amountInUSD; ?>" class="btn btn-primary">
                    Pay Now via PayPal
                </a>
            </div>
        </div>
    </div>
</body>
</html>
