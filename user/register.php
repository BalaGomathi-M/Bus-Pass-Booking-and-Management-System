<?php
include 'includes/dbconnection.php'; // Ensure dbconnection.php contains the database connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $emailCheckSQL = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($emailCheckSQL);
    $stmt->bind_param("s", $email); // Bind email as a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If email already exists, show an error
        $error = "This email is already registered. Please log in or use a different email.";
    } else {
        // If email does not exist, proceed with the registration
        $sql = "INSERT INTO users (username, email, mobile, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $mobile, $password);

        if ($stmt->execute()) {
            echo "Registration successful";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Register</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p> <!-- Display error if email already exists -->
    <?php endif; ?>

    <form method="post" action="register.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="mobile">Mobile:</label><br>
        <input type="text" id="mobile" name="mobile" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>
    <a href="index.php">Login</a>
</body>
</html>
