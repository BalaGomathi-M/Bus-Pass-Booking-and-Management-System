<!DOCTYPE html>
<html>
<head>
    <title>View Your Bus Pass</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
            text-align: center;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            display: inline-block;
            text-align: left;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
        }
        .download-button {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .download-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Your Bus Pass Details</h2>

    <?php
    session_start(); // Start the session
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Retrieve Pass Number from session
    $PASSNUMBER = isset($_SESSION['PASSNUMBER']) ? $_SESSION['PASSNUMBER'] : null;

    if (!empty($PASSNUMBER)) {
        $host = "localhost";  // Database host
        $dbusername = "root";  // Database username
        $dbpassword = "";  // Database password
        $dbname = "buspassdb";  // Database name

        // Create a new database connection
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

        // Check for any connection errors
        if ($conn->connect_error) {
            die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
        } else {
            // SQL query to fetch the pass details
            $sql = "SELECT FIRSTNAME, GMAIL, CONTACTNUMBER, PASSNUMBER, fromdate, todate, source, destination, ANYCOMMAND FROM tblrenewal WHERE PASSNUMBER = ?";

            // Prepare the SQL statement to prevent SQL injection
            if ($stmt = $conn->prepare($sql)) {
                // Change to string binding
                $stmt->bind_param("s", $PASSNUMBER);  // Use "s" for string
                $stmt->execute();
                $stmt->store_result();  // Store the result
                $stmt->bind_result($FIRSTNAME, $GMAIL, $CONTACTNUMBER, $passNumber, $fromdate, $todate, $source, $destination, $ANYCOMMAND);

                // Check if a pass with the given number exists
                if ($stmt->num_rows > 0) {
                    $stmt->fetch();  // Fetch the details
                    echo "<div class='result'>
                            <strong>Pass Details:</strong><br>
                            Name: $FIRSTNAME<br>
                            Email: $GMAIL<br>
                            Contact Number: $CONTACTNUMBER<br>
                            Pass Number: $passNumber<br>
                            Valid From: $fromdate<br>
                            Valid To: $todate<br>
                            Source: $source<br>
                            Destination: $destination<br>
                            Additional Info: $ANYCOMMAND<br>
                          </div>";


                          echo "<form action='payment_page.php' method='post'>
                          <input type='hidden' name='passnumber' value='$PASSNUMBER'>
                          <input type='submit' class='download-button' value='Make Payment'>
                        </form>";
                    
                    // Add a download button
                    echo "<form action='download_pass.php' method='post'>
                            <input type='hidden' name='passnumber' value='$PASSNUMBER'>
                            <input type='submit' class='download-button' value='Download Pass'>
                          </form>";
                } else {
                    echo "<p>No pass found with this number.</p>";
                }

                $stmt->close();  // Close the prepared statement
            } else {
                echo "Failed to prepare the SQL statement.";
            }

            $conn->close();  // Close the connection
        }
    } else {
        echo "No Pass Number provided.";
    }
    ?>
</div>
</body>
</html>
