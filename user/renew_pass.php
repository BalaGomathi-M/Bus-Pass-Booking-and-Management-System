<?php
session_start();
include 'includes/dbconnection.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $new_valid_until = date('Y-m-d', strtotime('+1 year'));

    $sql = "UPDATE bus_passes SET valid_until='$new_valid_until' WHERE email='$email'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Pass renewed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Renew Pass</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Renew Pass</h2>
    <form method="post" action="renew_pass.php">
        <input type="submit" value="Renew Pass">
    </form>
    <a href="home.php">Home</a>
</body>
</html>
