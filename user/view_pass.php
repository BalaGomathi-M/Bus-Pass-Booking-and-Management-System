<?php
session_start();
include 'includes/dbconnection.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM bus_passes WHERE email='$email'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Pass</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Your Bus Pass</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($pass = $result->fetch_assoc()): ?>
            <p>Pass ID: <?php echo $pass['id']; ?></p>
            <p>Route: <?php echo $pass['route']; ?></p>
            <p>Type: <?php echo $pass['type']; ?></p>
            <p>Valid Until: <?php echo $pass['valid_until']; ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You do not have any active passes.</p>
    <?php endif; ?>
    <a href="home.php">Home</a>
</body>
</html>
