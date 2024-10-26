<?php
session_start();
require_once('../tcpdf-main/tcpdf.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Database connection details
$host = "localhost"; // Database host
$dbusername = "root"; // Database username
$dbpassword = ""; // Database password
$dbname = "buspassdb"; // Database name

// Create a new database connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check for any connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user email from session
$email = $_SESSION['email'];

// Fetch pass details from tblpass based on the user's email
// Check if the pass status is 'Accepted'
$sql = "SELECT * FROM tblpass WHERE Email = ? AND Status = 'Accepted'";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Set the banner heading
        $pdf->SetFont('helvetica', 'B', 24); // Larger font size for the heading
        $pdf->SetTextColor(0, 51, 102); // Set text color (dark blue)
        $pdf->Cell(0, 10, 'Tirunelveli Bus Passes', 0, 1, 'C'); // Center the title
        $pdf->Ln(5); // Small space after heading
        
        // Add issuer information (if applicable)
        $pdf->SetFont('helvetica', 'I', 12);
        $pdf->Cell(0, 10, 'Issued by: Tirunelveli Transport Authority', 0, 1, 'C'); // Center the issuer info
        $pdf->Ln(5); // Add space after the issuer information (2 lines of space is 10, so we use 5 for 2 lines)

        // Set the PDF content font
        $pdf->SetFont('helvetica', '', 12);

        // Fetch the details of the pass
        while ($pass = $result->fetch_assoc()) {
            // Align image on the right side (150 mm from the left, 30 mm down from top, size: 40x40 mm)
            if (!empty($pass['ProfileImage'])) {
                $imageFile = '../uploadimg/' . $pass['ProfileImage']; // Adjust path as necessary
                if (file_exists($imageFile)) {
                    $pdf->Image($imageFile, 150, 30, 40, 40, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                }
            }

            // Pass details displayed on the left side
            $pdf->SetY(30); // Align text vertically with the image
            $pdf->SetX(15); // Set X to left margin
            
            // Now, let's display the pass details
            $pdf->Cell(0, 10, 'Pass Number: ' . $pass['PassNumber'], 0, 1);
            $pdf->Cell(0, 10, 'Full Name: ' . $pass['FullName'], 0, 1);
            $pdf->Cell(0, 10, 'Contact Number: ' . $pass['ContactNumber'], 0, 1);
            $pdf->Cell(0, 10, 'Email: ' . $pass['Email'], 0, 1);
            $pdf->Cell(0, 10, 'Source: ' . $pass['Source'], 0, 1);
            $pdf->Cell(0, 10, 'Destination: ' . $pass['Destination'], 0, 1);
            $pdf->Cell(0, 10, 'From Date: ' . $pass['FromDate'], 0, 1);
            $pdf->Cell(0, 10, 'To Date: ' . $pass['ToDate'], 0, 1);
            $pdf->Cell(0, 10, 'Amount: ' . $pass['amount'], 0, 1);
            $pdf->Cell(0, 10, 'Payment Status: ' . $pass['payment_status'], 0, 1);
            $pdf->Ln(10); // Add some space after pass details
        }

        // Close and output PDF document
        $pdf->Output('bus_pass.pdf', 'D'); // 'D' for download
    } else {
        echo "Your pass is either not accepted by the admin.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
