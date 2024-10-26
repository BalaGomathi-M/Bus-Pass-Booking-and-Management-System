<?php
session_start();
include 'includes/dbconnection.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $email = $_SESSION['email'];
    $sql = "SELECT password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($current_password, $row['password'])) {
            $sql = "UPDATE users SET password='$new_password' WHERE email='$email'";
            if ($conn->query($sql) === TRUE) {
                echo "Password updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Change Password</h2>
    <form method="post" action="change_password.php">
        Email:  <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>"
        <label for="current_password">Current Password:</label><br>
        <input type="password" id="current_password" name="current_password" required><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <input type="submit" value="Change Password">
    </form>
    <a href="home.php">Home</a>
    <a href="logout.php">Logout</a>
</body>
</html>
