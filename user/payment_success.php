<?php
session_start();
include('includes/dbconnection.php');

// PayPal sends back the custom field with PassNumber and other details
if (isset($_POST['custom']) && isset($_POST['txn_id']) && isset($_POST['payment_status'])) {
    $passNumber = $_POST['custom'];
    $transactionID = $_POST['txn_id'];
    $paymentStatus = $_POST['payment_status'];

    // Only process the payment if the status is "Completed"
    if ($paymentStatus === 'Completed') {
        // Retrieve the amount for the pass
        $sql = "SELECT amount FROM tblpass WHERE PassNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $passNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $amount = $row['amount'];

            // Insert payment details into tblpayment
            $sql_payment = "INSERT INTO tblpayment (PassNumber, Amount, PaymentStatus, TransactionID) VALUES (?, ?, 'Paid', ?)";
            $stmt_payment = $conn->prepare($sql_payment);
            $stmt_payment->bind_param("sds", $passNumber, $amount, $transactionID);
            $stmt_payment->execute();

            // Update payment status in tblpass
            $sql_update = "UPDATE tblpass SET payment_status = 'Paid' WHERE PassNumber = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("s", $passNumber);
            $stmt_update->execute();

            echo "Payment successful! Your pass has been activated.";
            // Provide a link to download the pass if needed
            echo '<a href="download_pass.php?passnumber=' . urlencode($passNumber) . '">Download your pass</a>';
        } else {
            echo "Pass not found!";
        }
    } else {
        echo "Payment not completed!";
    }
} else {
    echo "Invalid request.";
}
?>
