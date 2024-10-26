<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'buspassdb');
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Get the email from the GET request
$email = isset($_GET['GMAIL']) ? $_GET['GMAIL'] : '';

$response = [];

if ($email) {
    // Prepare and execute the query to fetch user data
    $stmt = $conn->prepare("SELECT FirstName, ContactNumber, PassNumber, Source, Destination, ToDate FROM tblpass WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($firstname, $contactNumber, $passNumber, $source, $destination, $toDate);

    if ($stmt->fetch()) {
        // Populate response with fetched data
        $response['success'] = true;
        $response['firstname'] = $firstname;
        $response['contactNumber'] = $contactNumber;
        $response['passNumber'] = $passNumber;
        $response['source'] = $source;
        $response['destination'] = $destination;
        $response['todate'] = $toDate;
    } else {
        $response['success'] = false; // No data found for this email
    }

    $stmt->close();
}

$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
