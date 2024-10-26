<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    // Debugging message to check form submission
    echo "Form Submitted"; 

    // Retrieving form input
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);

    // Debugging to ensure values are fetched correctly
    echo "Email: $email, Mobile: $mobile";

    // Query to check if email and mobile exist in the database
    $sql = "SELECT email FROM users WHERE email=:email AND mobile=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // Debugging message to check query execution
    echo "Query executed";

    // Check if any matching record found
    if ($query->rowCount() > 0) {
        echo "User Found"; // Debugging message

        // Update the user's password
        $con = "UPDATE users SET password=:newpassword WHERE email=:email AND mobile=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();

        echo "<script>alert('Your Password successfully changed');</script>";
    } else {
        echo "<script>alert('Email id or Mobile number is invalid');</script>"; 
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Bus Pass Booking & Management System | Forgot Password Page</title>
    <!-- Core CSS - Include with every page -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />

    <!-- Password validation script -->
    <script type="text/javascript">
        function valid() {
            if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                alert("New Password and Confirm Password fields do not match!");
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="body-Login-back">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center logo-margin">
                <strong style="color: white; font-size: 25px">Bus Pass Booking & Management System</strong>
            </div>
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Reset Your Password</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" name="chngpwd" onSubmit="return valid();">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-Mail" name="email" required="true" type="email">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Mobile Number" name="mobile" maxlength="10" pattern="[0-9]+" required="true" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="New Password" name="newpassword" required="true">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="Confirm Password" name="confirmpassword" required="true">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <a href="index.php">Already have an account?</a>
                                    </label>
                                </div>
                                <input type="submit" value="submit" class="btn btn-lg btn-success btn-block" name="submit">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts - Include with every page -->
    <script src="assets/plugins/jquery-1.10.2.js"></script>
    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
</body>

</html>