<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Management System | Application Submitted</title>
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
            max-width: 800px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #444;
        }

        .message {
            font-size: 1.2em;
            margin: 20px 0;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Application Submission</h1>
        <?php 
        if (isset($_POST['register'])) {
            $con = mysqli_connect("localhost", "root", "", "buspassdb");

            // Check connection
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Retrieve form data
            $name = mysqli_real_escape_string($con, $_POST['name']);
            $gender = mysqli_real_escape_string($con, $_POST['gender']);
            $Contactnumber = mysqli_real_escape_string($con, $_POST['ContactNumber']);
            $DOB = mysqli_real_escape_string($con, $_POST['DOB']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $aadhar_card = mysqli_real_escape_string($con, $_POST['aadhar_card']);
            $fromdate = mysqli_real_escape_string($con, $_POST['fromdate']);
            $Todate = mysqli_real_escape_string($con, $_POST['Todate']);
            $source = mysqli_real_escape_string($con, $_POST['source']);
            $destination = mysqli_real_escape_string($con, $_POST['destination']);
            $address = mysqli_real_escape_string($con, $_POST['address']);
            $amount = mysqli_real_escape_string($con, $_POST['cost']); // New line to get cost
            $image = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            
            // New: handle Student ID card upload
            $student_id_card = $_FILES['student_id_card']['name'];
            $student_id_card_tmp = $_FILES['student_id_card']['tmp_name'];

            // Check for existing application
            $checkQuery = "SELECT * FROM tblapplynewform WHERE Contactnumber = '$Contactnumber' OR email = '$email'";
            $checkResult = mysqli_query($con, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                // Fetch existing application details
                $existingApplication = mysqli_fetch_assoc($checkResult);
                $status = $existingApplication['status'];

                // Allow reapplication only if the status is "Rejected"
                if ($status !== 'Rejected') {
                    echo "<div class='message error'>You have already applied for a pass. Current Status: <strong>$status</strong>. Please wait for the status update or contact support for further assistance.</div>";
                    mysqli_close($con);
                    return; // Exit the script
                }
            }

            // Ensure upload directories exist
            if (!is_dir("../uploadimg/")) {
                mkdir("../uploadimg/", 0755, true); // Create directory if it doesn't exist
            }

            if (!is_dir("../student_id_cards/")) {
                mkdir("../student_id_cards/", 0755, true); // Create directory if it doesn't exist
            }

            // Move uploaded image and student ID card to their respective directories
            if (move_uploaded_file($image_tmp, "../uploadimg/$image") && move_uploaded_file($student_id_card_tmp, "../student_id_cards/$student_id_card")) {
                // Insert application data into the database
                $query = "INSERT INTO tblapplynewform (name, gender, Contactnumber, DOB, email, image, aadhar_card, student_id_card, fromdate, Todate, source, destination, address, amount, status) 
                          VALUES ('$name', '$gender', '$Contactnumber', '$DOB', '$email', '$image', '$aadhar_card', '$student_id_card', '$fromdate', '$Todate', '$source', '$destination', '$address', '$amount', 'Pending')";

                $result = mysqli_query($con, $query);

                if ($result) {
                    echo "<div class='message success'>
                            Your bus pass application has been submitted successfully. Please wait for the admin to review your application. You will be notified via email about the status of your pass.
                          </div>";
                } else {
                    echo "<div class='message error'>
                            There was an error submitting your application. Please try again later.
                          </div>";
                }
            } else {
                echo "<div class='message error'>
                        There was an error uploading your files. Please check the file types and sizes, and try again.
                      </div>";
            }

            mysqli_close($con);
        }
        ?>
    </div>
</body>

</html>
