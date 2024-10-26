<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['userid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $userid = $_SESSION['userid'];
        $fname = strtoupper($_POST['fullname']);
        $cnum = $_POST['cnumber'];
        $email = $_POST['email'];
        $itype = $_POST['identitytype'];
        $icnum = $_POST['icnum'];
        $source = $_POST['source'];
        $des = $_POST['destination'];
        $fdate = $_POST['fromdate'];
        $tdate = $_POST['todate'];
        $cost = $_POST['cost'];

        // Generate pass number
        $initial = substr($fname, 0, 1);
        $firstTwoLetters = substr($fname, 0, 2);
        $lastFourDigits = substr($icnum, -4);
        $passnum = $initial . $firstTwoLetters . $lastFourDigits;

        $propic = $_FILES["propic"]["name"];
        $extension = substr($propic, strlen($propic) - 4, strlen($propic));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Profile Pic has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $propic = md5($propic) . time() . $extension;
            move_uploaded_file($_FILES["propic"]["tmp_name"], "images/" . $propic);

            $sql = "INSERT INTO tblpass(UserID, PassNumber, FullName, ProfileImage, ContactNumber, Email, IdentityType, IdentityCardno, Source, Destination, FromDate, ToDate, Cost) 
                    VALUES (:userid, :passnum, :fname, :propic, :cnum, :email, :itype, :icnum, :source, :des, :fdate, :tdate, :cost)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':userid', $userid, PDO::PARAM_STR);
            $query->bindParam(':passnum', $passnum, PDO::PARAM_STR);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':cnum', $cnum, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':itype', $itype, PDO::PARAM_STR);
            $query->bindParam(':icnum', $icnum, PDO::PARAM_STR);
            $query->bindParam(':source', $source, PDO::PARAM_STR);
            $query->bindParam(':des', $des, PDO::PARAM_STR);
            $query->bindParam(':fdate', $fdate, PDO::PARAM_STR);
            $query->bindParam(':tdate', $tdate, PDO::PARAM_STR);
            $query->bindParam(':cost', $cost, PDO::PARAM_STR);
            $query->bindParam(':propic', $propic, PDO::PARAM_STR);

            $query->execute();

            $LastInsertId = $dbh->lastInsertId();
            if ($LastInsertId > 0) {
                echo '<script>alert("Pass detail has been added.")</script>';
                echo "<script>window.location.href ='add-pass.php'</script>";
            } else {
                echo '<script>alert("Something Went Wrong. Please try again")</script>';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Pass</title>
    <!-- Core CSS - Include with every page -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />
</head>
<body>
    <!-- wrapper -->
    <div id="wrapper">
        <!-- navbar top -->
        <?php include_once('includes/header.php');?>
        <!-- end navbar top -->

        <!-- navbar side -->
        <?php include_once('includes/sidebar.php');?>
        <!-- end navbar side -->
        <!-- page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <!-- page header -->
                <div class="col-lg-12">
                    <h1 class="page-header">Add New Pass</h1>
                </div>
                <!-- end page header -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Form Elements -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="fullname">Full Name</label>
                                            <input type="text" name="fullname" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="propic">Profile Image</label>
                                            <input type="file" name="propic" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="cnumber">Contact Number</label>
                                            <input type="text" name="cnumber" class="form-control" required='true' maxlength="10" pattern="[0-9]+">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" name="email" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="identitytype">Identity Type</label>
                                            <select name="identitytype" class="form-control" required='true'>
                                                <option value="">Choose Identity Type</option>
                                                <option value="Voter Card">Voter Card</option>
                                                <option value="PAN Card">PAN Card</option>
                                                <option value="Adhar Card">Adhar Card</option>
                                                <option value="Student Card">Student Card</option>
                                                <option value="Driving License">Driving License</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="icnum">Identity Card No.</label>
                                            <input type="text" name="icnum" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="source">Source</label>
                                            <input type="text" name="source" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="destination">Destination</label>
                                            <input type="text" name="destination" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="fromdate">From Date</label>
                                            <input type="date" name="fromdate" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="todate">To Date</label>
                                            <input type="date" name="todate" class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="cost">Cost</label>
                                            <input type="text" name="cost" class="form-control" required='true'>
                                        </div>
                                        <p style="padding-left: 450px">
                                            <button type="submit" class="btn btn-primary" name="submit" id="submit">Add</button>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Form Elements -->
                </div>
            </div>
        </div>
        <!-- end page-wrapper -->
    </div>
    <!-- end wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="assets/plugins/jquery-1.10.2.js"></script>
    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/plugins/pace/pace.js"></script>
    <script src="assets/scripts/siminta.js"></script>
</body>
</html>
<?php } ?>
