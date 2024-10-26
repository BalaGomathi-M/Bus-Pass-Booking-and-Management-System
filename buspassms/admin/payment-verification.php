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

// Fetch all pending payment records
$sql = "SELECT * FROM tblpayment WHERE PaymentStatus = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Verify Payments</title>
</head>
<body>
    <h1>Pending Payments</h1>
    <table border="1">
        <tr>
            <th>Payment ID</th>
            <th>Pass Number</th>
            <th>Amount</th>
            <th>Transaction ID</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['PaymentID']; ?></td>
                <td><?php echo $row['PassNumber']; ?></td>
                <td><?php echo $row['Amount']; ?></td>
                <td><?php echo $row['TransactionID']; ?></td>
                <td>
                    <form action="update_payment.php" method="post">
                        <input type="hidden" name="payment_id" value="<?php echo $row['PaymentID']; ?>">
                        <input type="hidden" name="pass_number" value="<?php echo $row['PassNumber']; ?>">
                        <button type="submit">Mark as Paid</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
