<?php
$passNumber = isset($_GET['passNumber']) ? $_GET['passNumber'] : '';

if (!$passNumber) {
    die("Pass number is required.");
}

$con = mysqli_connect("localhost", "root", "", "buspassdb");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM tblpass WHERE PassNumber = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $passNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $pass = $result->fetch_assoc();

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=pass_" . $passNumber . ".pdf");
    
    // Generate PDF content
    // For simplicity, this example uses plain text. In practice, use a library like FPDF or TCPDF.
    echo "Pass Number: " . htmlspecialchars($pass['PassNumber']) . "\n";
    echo "Full Name: " . htmlspecialchars($pass['FullName']) . "\n";
    echo "Contact Number: " . htmlspecialchars($pass['ContactNumber']) . "\n";
    echo "Email: " . htmlspecialchars($pass['Email']) . "\n";
    echo "Amount: $" . htmlspecialchars($pass['Amount']) . "\n";
    echo "Creation Date: " . htmlspecialchars($pass['PasscreationDate']) . "\n";
} else {
    echo "No pass details found.";
}

mysqli_close($con);
?>
