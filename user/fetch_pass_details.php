<?php
if (isset($_GET['GMAIL']) && isset($_GET['CONTACTNUMBER'])) {
    $gmail = $_GET['GMAIL'];
    $contact = $_GET['CONTACTNUMBER'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'buspassdb');
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }

    // Prepare and execute the query
    $sql = "SELECT FirstName, PassNumber FROM tblpass WHERE Email = ? AND ContactNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $gmail, $contact);
    $stmt->execute();
    $stmt->bind_result($firstName, $passNumber);

    $user = [];

    if ($stmt->fetch()) {
        $user['FirstName'] = $firstName;
        $user['PassNumber'] = $passNumber; // Found pass number
    }

    $stmt->close();
    $conn->close();

    if (!empty($user)) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
