<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Start the session (if you haven't already)
session_start();

// Database connection setup
$host = "localhost";  // Database host
$dbusername = "root";  // Database username
$dbpassword = "";  // Database password
$dbname = "buspassdb";  // Database name

// Initialize success and error message variables
$success = false;
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $GMAIL = $_POST['GMAIL']; // Get email from form
    $FIRSTNAME = $_POST['FIRSTNAME'];
    $CONTACTNUMBER = $_POST['CONTACTNUMBER'];
    $passNumber = $_POST['PASSNUMBER'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $ANYCOMMAND = $_POST['ANYCOMMAND'];

    // Create a new database connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    // Check for any connection errors
    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    } else {
        // Fetch pass number using email from the tblpass table
        $sql = "SELECT PassNumber FROM tblpass WHERE EMAIL = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $GMAIL);
            $stmt->execute();
            $stmt->bind_result($passNumber);
            $stmt->fetch();
            $stmt->close();
        }

        // Check if pass number was found
        if (isset($passNumber)) {
            // Update the pass details in tblrenewal
            $sqlRenew = "INSERT INTO tblrenewal (FIRSTNAME, GMAIL, CONTACTNUMBER, PASSNUMBER, fromdate, todate, source, destination, ANYCOMMAND) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmtRenew = $conn->prepare($sqlRenew)) {
                // Ensure the correct number of parameters and types
                $stmtRenew->bind_param("ssissssss", $FIRSTNAME, $GMAIL, $CONTACTNUMBER, $passNumber, $fromdate, $todate, $source, $destination, $ANYCOMMAND);
                if ($stmtRenew->execute()) {
                    $_SESSION['PASSNUMBER'] = $passNumber;
                    $success = true; // Set success to true

                    // Sending confirmation email using PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();                                         // Set mailer to use SMTP
                        $mail->Host       = 'smtp.gmail.com';                  // Specify main and backup SMTP servers
                        $mail->SMTPAuth   = true;                              // Enable SMTP authentication
                        $mail->Username   = 'gomathimbala003@gmail.com';       // SMTP username
                        $mail->Password   = 'crdknjpfvykvxhov';                // SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
                        $mail->Port       = 587;                               // TCP port to connect to

                        // Recipients
                        $mail->setFrom('gomathimbala003@gmail.com', 'Bus Pass System');
                        $mail->addAddress($GMAIL);                             // Add a recipient

                        // Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Bus Pass Renewal Confirmation';
                        $mail->Body    = "Dear $FIRSTNAME,<br>Your bus pass has been renewed successfully.<br>
                                          Pass Number: $passNumber<br>
                                          Valid From: $fromdate<br>
                                          Valid To: $todate<br>
                                          Source: $source<br>
                                          Destination: $destination<br>
                                          Additional Info: $ANYCOMMAND<br>Thank you for using our service!";

                        $mail->send(); // Send email
                        /*echo "<h1>✓ Success!</h1>";
                        echo "<p>Your bus pass has been renewed successfully.<br>";
                        echo "Email: $GMAIL<br>";
                        echo "Pass Number: $passNumber<br>";
                        echo "<a href='viewpass.php'>View Your Pass</a></p>";*/
                    } catch (Exception $e) {
                        echo "Error sending email: {$mail->ErrorInfo}";
                    }
                } else {
                    $errorMessage = "Error renewing your bus pass.";
                }
                $stmtRenew->close();
            }
        } else {
            $errorMessage = "No pass number found for the provided email.";
        }

        $conn->close(); // Close the connection
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Pass Renewal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #28a745;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: #555;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }

        .icon-success {
            font-size: 4em;
            color: #28a745;
            margin-bottom: 20px;
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2em;
            }

            p {
                font-size: 1em;
            }

            a {
                font-size: 0.9em;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <div class="icon-success">&#10003;</div> <!-- Success Checkmark Icon -->
            <h1>Success!</h1>
            <p>Your bus pass has been renewed successfully.</p>
            <p>Email: <?= htmlspecialchars($GMAIL) ?><br>Pass Number: <?= htmlspecialchars($passNumber) ?></p>
            <a href="viewpass.php?email=<?= urlencode($GMAIL) ?>">View Your Pass</a>
        <?php else: ?>
            <h1>Error</h1>
            <p>Error renewing your bus pass.<br>
               <?= htmlspecialchars($errorMessage) ?></p>
            <a href="renewal.php">Try Again</a>
        <?php endif; ?>
    </div>
</body>
</html>
