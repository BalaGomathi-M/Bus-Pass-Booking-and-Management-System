<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_input = $_POST['otp']; // OTP entered by the user

    // Check if the OTP entered matches the one stored in the session
    if ($otp_input == $_SESSION['otp']) {
        // Successful OTP verification
        echo "<h2>OTP Verified Successfully!</h2>";
        // Redirect to the homepage or dashboard
        header("Location: home.php"); // Redirect to a landing page after successful login
        exit();
    } else {
        $error = "Invalid OTP. Please try again."; // Show error for invalid OTP
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .otp-container {
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .otp-container h2 {
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

<div class="otp-container">
    <h2 class="text-center">Verify OTP</h2>

    <?php if (isset($error)): ?>
        <div class="error-message text-center">
            <?php echo htmlspecialchars($error); // Display error message safely ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-floating">
            <input type="text" class="form-control" id="floatingOtp" placeholder="Enter OTP" name="otp" required>
            <label for="floatingOtp">OTP</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Verify OTP</button>
    </form>

    <div class="mt-3 text-center">
        <a href="login.php">Back to Login</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
