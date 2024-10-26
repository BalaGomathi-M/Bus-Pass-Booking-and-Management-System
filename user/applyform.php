<!DOCTYPE html>
<html>

<head>
    <title>Bus Pass Management System | Apply New Pass</title>
    
    <!-- Include jQuery and jQuery UI -->
    <script type="text/javascript" src="jquery-1.4.2.js"></script>
    <script type="text/javascript" src="ui/jquery.ui.core.js"></script>
    <script type="text/javascript" src="ui/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="ui/jquery.ui.datepicker.js"></script>
    <link type="text/css" href="demos.css" rel="stylesheet" />
    
    <style>
        body {
            background-image: url("https://images.unsplash.com/photo-1583331989229-9cc35b3e3423?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fHBhdWx8ZW58MHx8MHx8&w=1000&q=80");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            backdrop-filter: blur(4px);
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 90%;
            margin: auto;
            background: rgba(255, 255, 255, 0.4);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h3 {
            color: #ffffff;
            text-align: center;
            margin: 0 0 20px;
        }

        form {
            margin: 20px 0;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            background-color: #007bff;
            padding: 5px;
        }

        input[type="text"], input[type="date"], textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: blue;
            color: white;
            border: none;
            padding: 15px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: darkblue;
        }

        .preview-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            display: none;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            input[type="submit"] {
                font-size: 14px;
                padding: 10px 15px;
            }
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <center>
        <h1>Apply New Pass Here / Renewal Here</h1>
        <h3>Welcome You</h3>
    </center>

    <div class="container">
    <?php
// Check if form is submitted
if (isset($_POST['register'])) {
    // Connect to the database
    $con = mysqli_connect("localhost", "root", "", "buspassdb");

    // Check for connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $Contactnumber = mysqli_real_escape_string($con, $_POST['Contactnumber']);
    $DOB = mysqli_real_escape_string($con, $_POST['DOB']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    //$identitytype = mysqli_real_escape_string($con, $_POST['identitytype']);
    //$icum = mysqli_real_escape_string($con, $_POST['icum']);
    $fromdate = mysqli_real_escape_string($con, $_POST['fromdate']);
    $Todate = mysqli_real_escape_string($con, $_POST['Todate']);
    $source = mysqli_real_escape_string($con, $_POST['source']);
    $destination = mysqli_real_escape_string($con, $_POST['destination']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $cost = mysqli_real_escape_string($con, $_POST['cost']); // Add the cost value
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Check for existing application with any status
    $checkQuery = "SELECT * FROM tblapplynewform WHERE Contactnumber = '$Contactnumber' OR email = '$email'";
    $checkResult = mysqli_query($con, $checkQuery);

    // If the user has already applied, check the status
    if (mysqli_num_rows($checkResult) > 0) {
        // Fetch existing application details
        $existingApplication = mysqli_fetch_assoc($checkResult);
        $status = $existingApplication['status'];

        // Allow reapplication only if the status is "Rejected"
        if ($status === 'Rejected') {
            echo "<h2 class='error-message'>Your previous application was rejected. You can reapply now.</h2>";
            // Proceed to insert the new application
        } else {
            echo "<h2 class='error-message'>You have already applied for a pass. Current Status: <strong>$status</strong>. Please wait for the status update or contact support for further assistance.</h2>";
            return; // Exit the script
        }
    }

    // Ensure the image is uploaded to the "images" directory
    $image_dir = "../buspassms/uploadimg/"; // Folder where the images will be saved
    $image_path = $image_dir . basename($image);

    if (move_uploaded_file($image_tmp, $image_path)) {
        // Insert into the database
        $query = "INSERT INTO tblapplynewform (name, gender, Contactnumber, DOB, email, image, identitytype, icum, fromdate, Todate, source, destination, address, cost, status)
                  VALUES ('$name', '$gender', '$Contactnumber', '$DOB', '$email', '$image_path', '$identitytype', '$icum', '$fromdate', '$Todate', '$source', '$destination', '$address', '$cost', 'Pending')";

        $result = mysqli_query($con, $query);

        if ($result) {
            echo "<h2>Pass Applied Successfully! Please wait for the status update.</h2>";
            echo "<h3>Your Uploaded Image:</h3>";
            echo "<img src='$image_path' alt='Uploaded Image' style='width:150px; height:150px;'>";
        } else {
            echo "<h2>Failed to apply for the pass. Please try again.</h2>";
        }
    } else {
        echo "<h2>Image upload failed! Please try again.</h2>";
    }

    // Close the database connection
    mysqli_close($con);
}
?>


        <form action="action.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required />

            <label>Gender:</label>
            <input type="radio" name="gender" value="male" /> Male
            <input type="radio" name="gender" value="female" /> Female
            <input type="radio" name="gender" value="transgender" /> Transgender

            <label for="ContactNumber">Contact Number:</label>
            <input type="text" id="ContactNumber" name="ContactNumber" maxlength="10" pattern="\d{10}" title="Please enter exactly 10 digits" required />

            <label for="DOB">Date of Birth:</label>
            <input type="date" id="DOB" name="DOB" required />

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required />

            <label for="image">Image:</label>
                <input type="file" id="profile-img" name="image" accept=".jpg, .jpeg, .png" required />
                <img src="" id="profile-img-tag" class="preview-img" />

           <!-- <label for="identitytype">Identity Type:</label>
            <select id="identitytype" name="identitytype" class="form-control" required>
                <option value="">Choose Identity Type</option>
                <option value="Voter Card">Voter Card</option>
                <option value="PAN Card">PAN Card</option>
                <option value="Adhar Card">Adhar Card</option>
                <option value="Student Card">Student Card</option>
                <option value="Driving License">Driving License</option>
                <option value="Passport">Passport</option>
                <option value="Any Official Card">Any Official Card</option>
                <option value="Any Other Govt Issued Doc">Any Other Govt Issued Doc</option>
            </select> -->

            <label for="aadhar_card">Aadhaar Card Number:</label>
        <input type="text" id="aadhar_card" name="aadhar_card" required>

        <label for="student_id_card">Upload Student ID Card (PDF, JPEG, PNG):</label>
        <input type="file" id="student_id_card" name="student_id_card" accept=".pdf, .jpeg, .jpg, .png" required>

            <label for="fromdate">From Date:</label>
            <input type="date" id="fromdate" name="fromdate" required />

            <label for="Todate">To Date:</label>
            <input type="date" id="Todate" name="Todate" required readonly/>

            <label for="source">Source:</label>
            <select id="source" name="source" required>
                <!-- Options populated by PHP -->
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

            <label for="destination">Destination:</label>
            <select id="destination" name="destination" required>
                <!-- Options populated by PHP -->
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

            <label for="cost">Cost:</label>
            <input type="text" id="cost" name="cost" readonly />

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required />

            <input type="submit" name="register" value="Submit" />
        </form>

        <script>
        document.getElementById('fromdate').addEventListener('change', function () {
    const fromDate = new Date(this.value);
    const toDate = new Date(fromDate);

    // Set toDate to one month ahead
    toDate.setMonth(toDate.getMonth() + 1); 

    // Set toDate to the day before the same day next month
    toDate.setDate(toDate.getDate() - 1); 

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
    </div>
</body>

</html>
