<?php
session_start();
require_once('../tcpdf-main/tcpdf.php'); // Adjusted path to your TCPDF

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

// Prepare the SQL statement to fetch user details and image
$sql = "SELECT r.FIRSTNAME, r.GMAIL, r.CONTACTNUMBER, r.PASSNUMBER, r.fromdate, r.todate, r.source, r.destination, p.ProfileImage 
        FROM tblrenewal AS r 
        LEFT JOIN tblpass AS p ON r.GMAIL = p.Email 
        WHERE r.GMAIL = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute();
    
    // Get the result set from the prepared statement
    $result = $stmt->get_result();

    // Check if any passes are found
    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Set the banner heading
        $pdf->SetFont('helvetica', 'B', 24); // Larger font size for the heading
        $pdf->SetTextColor(0, 51, 102); // Set text color (dark blue)
        $pdf->Cell(0, 10, 'Tirunelveli Bus Pass Renewal', 0, 1, 'C'); // Center the title
        $pdf->Ln(5); // Small space after heading
        
        // Add issuer information (if applicable)
        $pdf->SetFont('helvetica', 'I', 12);
        $pdf->Cell(0, 10, 'Issued by: Tirunelveli Transport Authority', 0, 1, 'C'); // Center the issuer info
        $pdf->Ln(5); // Add space after the issuer information

        // Set the PDF content font
        $pdf->SetFont('helvetica', '', 12);
        
        // Fetch the details of the renewal pass
        $pass = $result->fetch_assoc();

        // Check if the required fields exist in the result
        if (isset($pass['FIRSTNAME'], $pass['GMAIL'], $pass['CONTACTNUMBER'], $pass['PASSNUMBER'], $pass['fromdate'], $pass['todate'], $pass['source'], $pass['destination'], $pass['ProfileImage'])) {
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
            $pdf->Cell(0, 10, 'Name: ' . $pass['FIRSTNAME'], 0, 1);
            $pdf->Cell(0, 10, 'Email: ' . $pass['GMAIL'], 0, 1);
            $pdf->Cell(0, 10, 'Contact Number: ' . $pass['CONTACTNUMBER'], 0, 1);
            $pdf->Cell(0, 10, 'Pass Number: ' . $pass['PASSNUMBER'], 0, 1);
            $pdf->Cell(0, 10, 'Valid From: ' . $pass['fromdate'], 0, 1);
            $pdf->Cell(0, 10, 'Valid To: ' . $pass['todate'], 0, 1);
            $pdf->Cell(0, 10, 'Source: ' . $pass['source'], 0, 1);
            $pdf->Cell(0, 10, 'Destination: ' . $pass['destination'], 0, 1);
            $pdf->Ln(10); // Add some space after pass details
        } else {
            echo "Required pass details are missing.";
        }

        // Output the PDF as a download
        $pdf->Output('bus_renewpass.pdf', 'D'); // 'D' for download, 'I' for inline
    } else {
        echo "No active pass found.";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Handle errors in preparing the statement
    echo "Error preparing statement: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
