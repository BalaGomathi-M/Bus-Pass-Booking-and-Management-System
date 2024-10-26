<?php
// Load Composer's autoloader


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
include('includes/dbconnection.php');

// Handle form submission
if (isset($_POST['send_notifications'])) {

    // Prepare the SQL query to get users whose pass is expiring soon (7, 5, 2 days before expiry)
    $query = "SELECT FullName, Email, ToDate FROM tblpass WHERE ToDate(CURDATE(), INTERVAL 7 DAY)";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row['FullName'];
            $email = $row['Email'];
            $expiryDate = $row['ToDate'];

            // Send renewal notification
            sendEmailNotification($name, $email, $expiryDate);
        }
        echo "Renewal notifications sent!";
    } else {
        echo "No users found with expiring bus passes.";
    }
}

// Function to send email notification using PHPMailer
function sendEmailNotification($name, $email, $expiryDate) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'gomathimbala003@gmail.com';  // Your Gmail address
        $mail->Password = 'crdknjpfvykvxhov';  // Your Gmail app-specific password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email headers and content
        $mail->setFrom('gomathimbala003@gmail.com', 'Bus Pass Management');
        $mail->addAddress($email, $name);  // Add recipient's email and name

        // Email subject and body
        $mail->isHTML(true);
        $mail->Subject = 'Bus Pass Renewal Notification';
        $mail->Body = "
            <h3>Dear $name,</h3>
            <p>Your bus pass is expiring on <strong>$expiryDate</strong>.</p>
            <p>Please renew your pass before the expiration date to avoid any inconvenience.</p>
            <br>
            <p>Thank you,<br>Bus Pass Management Team</p>
        ";

        $mail->send();
        echo "Email sent to $email.<br>";

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
    }
}
