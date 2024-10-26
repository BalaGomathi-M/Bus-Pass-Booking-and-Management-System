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

// Prepare and execute query to get applicant's email, name, and identity card number
$emailQuery = $con->prepare("SELECT * FROM tblapplynewform WHERE id = ?");
$emailQuery->bind_param("i", $id);
$emailQuery->execute();
$emailResult = $emailQuery->get_result();

if ($emailResult && $emailResult->num_rows > 0) {
    $emailRow = $emailResult->fetch_assoc();
    $applicantEmail = $emailRow['email'];
    $applicantName = $emailRow['name'];
    $contact = $emailRow['Contactnumber'];
    $source = $emailRow['source'];
    $aadhar = $emailRow['aadhar_card'];
    //$icum = $emailRow['icum']; // Identity Card Number
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to retrieve applicant information.';
    echo json_encode($response);
    exit();
}

// Generate pass number
$initial = substr($applicantName, 0, 1); // Initial of the name
$firstTwoLetters = substr($applicantName, 0, 2); // First two letters of the name
$lastFourDigits = substr($aadhar, -4); // Last four digits of the identity card number
$passNumber = $initial . $firstTwoLetters . $lastFourDigits;

// Prepare the status and update queries
if ($action == 'accept') {
    $status = 'Accepted';
    $updateQuery = "UPDATE tblapplynewform SET status = ? WHERE id = ?";
    $insertQuery = "INSERT INTO tblpass (PassNumber, FullName, ContactNumber, Email, PasscreationDate, Status, Source) 
                    VALUES ('3434', 'dgdrg', 'sfwrffs', 'serfsc', NOW(), 'serfsc', 'dgdvdrv')";
    $subject = "Your Bus Pass Application Status";
    $message = "Dear $applicantName,\n\nYour bus pass application has been accepted.\n\nYour pass number is: $passNumber\n\nThank you.";
} elseif ($action == 'reject') {
    $status = 'Rejected';
    $updateQuery = "UPDATE tblapplynewform SET status = ?, rejection_reason = ? WHERE id = ?";
    $insertQuery = "INSERT INTO tblpass (PassNumber, FullName, ContactNumber, Email, PasscreationDate, Status) 
                    VALUES (?, ?, ?, ?, NOW(), ?)";
    $subject = "Your Bus Pass Application Status";
    $message = "Dear $applicantName,\n\nYour bus pass application has been rejected due to the following reason: $rejection_reason.\n\nThank you.";
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid action';
    echo json_encode($response);
    exit();
}

// Execute the update query for tblapplynewform
if ($stmt = $con->prepare($updateQuery)) {
    if ($action == 'reject') {
        $stmt->bind_param("ssi", $status, $rejection_reason, $id);
    } else {
        $stmt->bind_param("si", $status, $id);
    }
    $stmt->execute();
    $stmt->close();

    // Execute the insert query for tblpass
    $insertStmt = $con->prepare($insertQuery);
    // $insertStmt->bind_param("ssssss", $passNumber, $applicantName, $contact, $applicantEmail, $status, $source, $aadhar);
    if ($insertStmt->execute()) {
        $response['insert_status'] = 'success';
    } else {
        $response['insert_status'] = 'error: ' . mysqli_error($con);
    }
    $insertStmt->close();

    // Prepare and send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gomathimbala003@gmail.com'; // SMTP username
        $mail->Password   = 'crdknjpfvykvxhov'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('gomathimbala003@gmail.com', 'Bus Pass System');
        $mail->addAddress($applicantEmail, $applicantName);

        // Content
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        // Success response
        $response['status'] = 'success';
        $response['message'] = 'Pass ' . $status . ' and notification sent';
    } catch (Exception $e) {
        // Success but email failed
        $response['status'] = 'warning';
        $response['message'] = 'Pass ' . $status . ', but email notification failed: ' . $mail->ErrorInfo;
    }
} else {
    // Error response
    $response['status'] = 'error';
    $response['message'] = 'Failed to update status: ' . mysqli_error($con);
}

// Close connection
mysqli_close($con);

// Output response as JSON
echo json_encode($response);

?>
