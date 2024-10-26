<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bus Pass</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
            padding-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5em;
            text-align: center;
            padding: 15px;
        }
        .card-body {
            padding: 30px;
        }
        .card-body h3 {
            color: #28a745;
            margin-bottom: 20px;
        }
        .card-body p {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 15px;
        }
        .alert {
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            font-size: 1.1em;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            font-size: 1.1em;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .card-body h3.rejected {
            color: #dc3545;
        }
        .pending-msg {
            color: #ffc107;
            font-size: 1.3em;
        }
    </style>
</head>
<body>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<div class='container'><div class='alert alert-danger'>User is not logged in. Please log in to view your pass.</div></div>";
    exit();
}

// Database connection
$con = mysqli_connect("localhost", "root", "", "buspassdb");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the user email from the session
$email = $_SESSION['email'];

// Fetch pass application details based on the logged-in user's email
$sql = "SELECT * FROM tblapplynewform WHERE email = '$email'";
$query = $con->query($sql);

if (!$query) {
    die("<div class='container'><div class='alert alert-danger'>Query failed: " . mysqli_error($con) . "</div></div>");
}

// Check if the query returned a result from tblapplynewform
if ($row = $query->fetch_assoc()) {
    $status = $row['status'];
    $rejection_reason = $row['rejection_reason'];

    echo '<div class="container">';
    echo '<div class="card">';
    echo '<div class="card-header">Bus Pass Status</div>';
    echo '<div class="card-body">';

    // If the pass is accepted, fetch details from tblpass
    if ($status == 'Accepted') {
        // Query tblpass table to get the PassNumber and payment_status
        $pass_query = "SELECT PassNumber, payment_status FROM tblpass WHERE Email = '$email'";
        $pass_result = $con->query($pass_query);

        if (!$pass_result) {
            die("<div class='alert alert-danger'>Query failed: " . mysqli_error($con) . "</div>");
        }

        // Check if the query returned a result from tblpass
        if ($pass_row = $pass_result->fetch_assoc()) {
            $pass_number = $pass_row['PassNumber'];
            $payment_status = $pass_row['payment_status'];

            echo "<h3>Pass Accepted</h3>";
            echo "<p><strong>Pass Number:</strong> $pass_number</p>";

            // Store the pass number in the session for payment page
            $_SESSION['PassNumber'] = $pass_number;

            // Show payment or download button based on payment status
            if ($payment_status == 'Not Paid') {
                // Payment form
                echo '<form action="payment_page.php" method="POST">
                        <input type="hidden" name="pass_number" value="' . $pass_number . '">
                        <button type="submit" class="btn btn-primary">Make Payment</button>
                      </form>';
            } else {
                // After payment, show download button
                echo '<a href="download-pass.php?pass_id=' . $row['id'] . '" class="btn btn-success">Download Pass</a>';
            }
        } else {
            echo "<div class='alert alert-warning'>No pass details found in tblpass.</div>";
        }
    } elseif ($status == 'Rejected') {
        echo "<h3 class='rejected'>Pass Rejected</h3>";
        echo "<p><strong>Reason:</strong> $rejection_reason</p>";
    } else {
        echo "<h3 class='pending-msg'>Your pass request is still pending</h3>";
    }

    echo '</div></div></div>';
} else {
    // No pass application found for the user
    echo "<div class='container'><div class='alert alert-warning'>No pass found for the logged-in user.</div></div>";
}

// Close the database connection
mysqli_close($con);
?>

<!-- Bootstrap JS and dependencies (Optional for additional Bootstrap features) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
