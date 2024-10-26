<?php
// get_passnumber.php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'buspassdb');
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Get parameters
$email = $_GET['GMAIL'];
$contact = $_GET['CONTACTNUMBER'];

// Prepare and execute the query
$sql = "SELECT passnumber FROM tblpass WHERE Email = ? AND ContactNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $contact);
$stmt->execute();
$stmt->bind_result($passNumber);

$response = [];

if ($stmt->fetch()) {
    $response['success'] = true;
    $response['passNumber'] = $passNumber; // Found pass number
} else {
    $response['success'] = false; // Not found
}

$stmt->close();
$conn->close();

echo json_encode($response);
