<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

// Database connection
$con = mysqli_connect("localhost", "root", "", "buspassdb");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get POST data
$action = isset($_POST['action']) ? $_POST['action'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$rejection_reason = isset($_POST['rejection_reason']) ? mysqli_real_escape_string($con, $_POST['rejection_reason']) : '';

// Initialize response
$response = array();

// Fetch applicant details
$emailQuery = $con->prepare("SELECT * FROM tblapplynewform WHERE id = ?");
$emailQuery->bind_param("i", $id);
$emailQuery->execute();
$emailResult = $emailQuery->get_result();

if ($emailResult && $emailResult->num_rows > 0) {
    $emailRow = $emailResult->fetch_assoc();
    $applicantEmail = $emailRow['email'];
    $applicantName = $emailRow['name'];
    $contact = $emailRow['Contactnumber'];
    $aadhar = $emailRow['aadhar_card'];
    $source = $emailRow['source'];
    $destination = $emailRow['destination'];
    $fromdate = $emailRow['fromdate'];
    $todate = $emailRow['Todate'];
    $studentidcard = $emailRow['student_id_card'];
    $image = $emailRow['image'];
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to retrieve applicant information.';
    echo json_encode($response);
    exit();
}

// Fetch the cost based on the route
$costQuery = $con->prepare("SELECT cost FROM tblcost WHERE source = ? AND destination = ?");
$costQuery->bind_param("ss", $source, $destination);
$costQuery->execute();
$costResult = $costQuery->get_result();

if ($costResult && $costResult->num_rows > 0) {
    $costRow = $costResult->fetch_assoc();
    $amount = $costRow['cost'];
} else {
    $response['status'] = 'error';
    $response['message'] = 'No cost found for the selected route.';
    echo json_encode($response);
    exit();
}

// Update the amount in tblapplynewform
$updateAmountQuery = "UPDATE tblapplynewform SET amount = ? WHERE id = ?";
$amountStmt = $con->prepare($updateAmountQuery);
$amountStmt->bind_param("di", $amount, $id);
$amountStmt->execute();
$amountStmt->close();

// Process Pass Application (Accept or Reject)
if ($action == 'accept') {
    $status = 'Accepted';
    $passNumber = strtoupper(substr($applicantName, 0, 4)) . substr($aadhar, -4);

    // Update pass status in tblapplynewform
    $updateQuery = "UPDATE tblapplynewform SET status = ? WHERE id = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("si", $status, $id);
    $updateStmt->execute();
    $updateStmt->close();

    // Insert pass into tblpass
    $insertQuery = "INSERT INTO tblpass(PassNumber, FullName, ContactNumber, Email, PasscreationDate, Status, Amount, Source, Destination, aadhar_card, FromDate, ToDate, student_id_card, ProfileImage) 
                    VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $con->prepare($insertQuery);
    $insertStmt->bind_param("sssssdsssssss", $passNumber, $applicantName, $contact, $applicantEmail, $status, $amount, $source, $destination, $aadhar, $fromdate, $todate, $studentidcard, $image);
    $insertStmt->execute();
    $insertStmt->close();

    // Generate payment link
    $paymentLink = "http://localhost/payment.php?passNumber=$passNumber&amount=$amount";

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gomathimbala003@gmail.com';
        $mail->Password = 'crdknjpfvykvxhov';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('gomathimbala003@gmail.com', 'Bus Pass System');
        $mail->addAddress($applicantEmail, $applicantName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Bus Pass Application Accepted';
        $mail->Body = "Dear $applicantName,<br><br>Your bus pass application has been accepted. Your pass number is <strong>$passNumber</strong>.<br>The amount for the pass is <strong>Rs.$amount</strong>.<br><br>You can make the payment and download your pass using the following link:<br><a href='$paymentLink'>Make Payment</a><br><br>Thank you.";

        $mail->send();
        $response['status'] = 'success';
        $response['message'] = 'Pass accepted and email sent successfully.';
        $response['passNumber'] = $passNumber;
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Pass accepted but email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
} elseif ($action == 'reject') {
    $status = 'Rejected';

    // Update rejection reason in tblapplynewform
    $updateQuery = "UPDATE tblapplynewform SET status = ?, rejection_reason = ? WHERE id = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("ssi", $status, $rejection_reason, $id);
    $updateStmt->execute();
    $updateStmt->close();

    // Send rejection email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gomathimbala003@gmail.com';
        $mail->Password = 'crdknjpfvykvxhov';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('gomathimbala003@gmail.com', 'Bus Pass System');
        $mail->addAddress($applicantEmail, $applicantName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Bus Pass Application Rejected';
        $mail->Body = "Dear $applicantName,<br><br>Your bus pass application has been rejected.<br>Reason: <strong>$rejection_reason</strong>.<br><br>Thank you.";

        $mail->send();
        $response['status'] = 'success';
        $response['message'] = 'Pass rejected and email sent successfully.';
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Pass rejected but email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

echo json_encode($response);
?>
