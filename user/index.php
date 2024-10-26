<?php
session_start(); // Start the session to track user login status
include 'includes/dbconnection.php'; // Include database connection (ensure $conn is set up here)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Trim to remove leading/trailing spaces
    $password = $_POST['password']; // Raw password from form

    // Prepare SQL query to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind email as a string
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists in the database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $row['password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $row['id']; // Store user id in session
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Redirect to home page after successful login
            header("Location: home.php");
            exit(); // Always exit after a header redirection to prevent further script execution
        } else {
            $error = "Invalid password. Please try again."; // Show error for wrong password
        }
    } else {
        $error = "No user found with this email address."; // Show error if user not found
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .login-container {
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .form-floating {
            margin-bottom: 15px;
        }
        .btn {
            width: 100%;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2 class="text-center">Login</h2>

        <?php if (isset($error)): ?>
            <div class="error-message text-center">
                <?php echo htmlspecialchars($error); // Display error message safely ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingEmail" placeholder="Email" name="email" required>
                <label for="floatingEmail">Email</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
        </form>

        <div class="mt-3 text-center">
            Don't have an account:<a href="register.php">Register</a>
        </div>
        <div class="mt-3 text-center">
            <a href="change_password.php">Forgot Password?</a>
        </div>
        <div class="mt-3 text-center">
            <a href="../index.php">Back Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
