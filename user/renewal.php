<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Management System || RENEWAL PAGE</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            min-height: 100%;
            font-family: Arial, sans-serif;
        }

        .b {
            background-image: url("https://prod-cdn-05.storenvy.com/product_photos/60404232/file_7d528c844a_original.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: flex-start; 
            padding-top: 50px; 
            padding-bottom: 50px;
        }

        h2 {
            color: white;
            font-size: 2em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px #000;
        }

        form {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 350px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px; 
        }

        label {
            text-align: left;
            width: 100%;
            font-size: 1em;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        a {
            color: white;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            form {
                width: 90%;
            }
        }
    </style>
</head>
<?php
session_start();
include('includes/dbconnection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['FIRSTNAME'];
    $gmail = $_POST['GMAIL'];
    $contactNumber = $_POST['CONTACTNUMBER'];
    $passNumber = $_POST['PASSNUMBER'];
    $fromDate = $_POST['fromdate'];
    $toDate = $_POST['todate'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $cost = $_POST['cost'];
    $anyCommand = $_POST['ANYCOMMAND'];

    // Check if the user has already renewed the pass
    $sql = "SELECT * FROM tblrenewals WHERE PASSNUMBER = ? LIMIT 1"; // Assuming tblrenewals tracks renewals
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $passNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User has already renewed the pass
        echo "<script>alert('You have already renewed your pass.'); window.location.href='renewal_page.php';</script>";
        exit();
    }

    // Insert new renewal record if not already renewed
    $insertSQL = "INSERT INTO tblrenewals (FIRSTNAME, GMAIL, CONTACTNUMBER, PASSNUMBER, FROMDATE, TODATE, SOURCE, DESTINATION, COST, ANYCOMMAND) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSQL);
    $insertStmt->bind_param("ssssssssss", $firstName, $gmail, $contactNumber, $passNumber, $fromDate, $toDate, $source, $destination, $cost, $anyCommand);
    
    if ($insertStmt->execute()) {
        echo "<script>alert('Renewal successful!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error renewing the pass. Please try again.'); window.location.href='renewal_page.php';</script>";
    }
    
    // Close statements
    $stmt->close();
    $insertStmt->close();
    $conn->close();
}
?>

<body class="b">
    <center>
        <h2>RENEWAL HERE</h2><br><br>

        <form name="renewalform" action="renewal1.php" method="POST">
            <label for="FIRSTNAME">FIRST NAME:</label>
            <input type="text" id="FIRSTNAME" name="FIRSTNAME" placeholder="FIRSTNAME" required>

            <label for="GMAIL">GMAIL:</label>
            <input type="email" id="GMAIL" name="GMAIL" placeholder="GMAIL" required>

            <label for="CONTACTNUMBER">CONTACT NUMBER:</label>
            <input type="text" maxlength="10" pattern="\d{10}" title="Please enter exactly 10 digits" id="CONTACTNUMBER" name="CONTACTNUMBER" placeholder="CONTACT NUMBER" required>

            <label for="PASSNUMBER">PASS NUMBER:</label>
            <input type="text" id="PASSNUMBER" name="PASSNUMBER" placeholder="PASS NUMBER" required>

            <label for="fromdate">FROM DATE:</label>
            <input type="date" id="fromdate" name="fromdate" placeholder="FROM DATE" required>

            <label for="todate">TO DATE:</label>
            <input type="date" id="todate" name="todate" placeholder="TO DATE" required>

            <label for="source">SOURCE:</label>
            <select id="source" name="source" required>
            <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'buspassdb');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch distinct source locations from the tblcost table
                $sql = "SELECT DISTINCT source FROM tblcost";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['source']) . "'>" . htmlspecialchars($row['source']) . "</option>";
                    }
                } else {
                    echo "<option value=''>No sources available</option>";
                }

                $conn->close();
                ?>
            </select>

            <label for="destination">DESTINATION:</label>
            <select id="destination" name="destination" required>
            <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'buspassdb');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch distinct destination locations from the tblcost table
                $sql = "SELECT DISTINCT destination FROM tblcost";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['destination']) . "'>" . htmlspecialchars($row['destination']) . "</option>";
                    }
                } else {
                    echo "<option value=''>No destinations available</option>";
                }

                $conn->close();
                ?>
            </select>

            <label for="cost">COST:</label>
            <input type="text" id="cost" name="cost" placeholder="COST" readonly required>

            <label for="ANYCOMMAND">ANY COMMENT:</label>
            <input type="text" id="ANYCOMMAND" name="ANYCOMMAND" placeholder="ANY COMMENT" required>

            <input type="submit" value="Renew Pass">

            <a href="index.php">HOME PAGE</a>
        </form>

        <script>
            // Function to fetch pass number based on GMAIL and populate other fields
            document.getElementById('GMAIL').addEventListener('blur', function() {
                const gmail = this.value;
                if (gmail) {
                    // Perform an AJAX request to fetch user data
                    const xhr = new XMLHttpRequest();
                    xhr.open("GET", "fetch_user_data.php?GMAIL=" + encodeURIComponent(gmail), true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                document.getElementById('FIRSTNAME').value = data.firstname;
                                document.getElementById('CONTACTNUMBER').value = data.contactNumber;
                                document.getElementById('PASSNUMBER').value = data.passNumber;
                                document.getElementById('source').value = data.source;
                                document.getElementById('destination').value = data.destination;
                                document.getElementById('todate').value = data.todate;
                            } else {
                                alert('No data found for this email.');
                            }
                        } else {
                            alert('Error fetching data.');
                        }
                    };
                    xhr.send();
                }
            });
        </script>
        <script>
        document.getElementById('fromdate').addEventListener('change', function () {
            const fromDate = new Date(this.value);
            const toDate = new Date(fromDate);
            toDate.setMonth(toDate.getMonth() + 1); // Add one month
            const year = toDate.getFullYear();
            const month = String(toDate.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed, so add 1
            const day = String(toDate.getDate()).padStart(2, '0');
            document.getElementById('Todate').value = `${year}-${month}-${day}`; // Set the value in YYYY-MM-DD format
        });
    </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var sourceSelect = document.getElementById('source');
                var destinationSelect = document.getElementById('destination');
                var costInput = document.getElementById('cost');

                // JavaScript object to hold cost details
                var costs = <?php
                    // PHP to generate the JavaScript object
                    $conn = new mysqli('localhost', 'root', '', 'buspassdb');
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT source, destination, cost FROM tblcost";
                    $result = $conn->query($sql);

                    $costs = array();
                    while ($row = $result->fetch_assoc()) {
                        $source = $row['source'];
                        $destination = $row['destination'];
                        $cost = $row['cost'];
                        if (!isset($costs[$source])) {
                            $costs[$source] = array();
                        }
                        $costs[$source][$destination] = $cost;
                    }

                    $conn->close();
                    echo json_encode($costs);
                ?>;

                function calculateCost() {
                    var source = sourceSelect.value;
                    var destination = destinationSelect.value;

                    if (source && destination && costs[source] && costs[source][destination]) {
                        costInput.value = costs[source][destination];
                    } else {
                        costInput.value = 'N/A'; // Default value if no cost found
                    }
                }

                // Event listeners for dropdown changes
                sourceSelect.addEventListener('change', calculateCost);
                destinationSelect.addEventListener('change', calculateCost);

                // JavaScript for displaying image preview
                document.getElementById('profile-img').addEventListener('change', function (event) {
                    var imagePreview = document.getElementById('profile-img-tag');
                    var file = event.target.files[0]; // Get the file

                    if (file) {
                        var reader = new FileReader(); // Create a new FileReader object
                        
                        reader.onload = function (e) {
                            // Set the image preview source to the loaded file data
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block'; // Show the image
                        };

                        reader.readAsDataURL(file); // Read the file as a DataURL
                    } else {
                        imagePreview.style.display = 'none'; // Hide the image preview if no file is selected
                    }
                });
            });
        </script>
    </center>
</body>
</html>
