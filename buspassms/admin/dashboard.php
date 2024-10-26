<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Booking & Management System | Dashboard</title>

    <!-- Fonts & Core CSS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f4f4;
}

/* Navbar adjustments */
#navbar {
    margin-bottom: 20px; /* Ensure there's space below the navbar */
}

/* Sidebar adjustments 
.sidebar-collapse {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px; /* Adjust the width as needed 
    height: 100%;
    background-color: #2c3e50;
    color: #fff;
    padding-top: 60px; /* Ensure sidebar content is not hidden behind the navbar 
} */




/* Styling Cards */
.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease-in-out;
    height: 150px; /* Fixed height */
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-3px);
}

/* Card Icon Styling */
.card .icon {
    font-size: 30px;
    margin-bottom: 10px;
    color: #3498db;
}

/* Number displayed inside cards */
.card .number {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
}

/* Card Link */
.card a {
    display: block;
    margin-top: 5px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    color: #3498db;
    transition: color 0.3s ease;
}

.card a:hover {
    color: #2980b9;
}

/* Grid Layout for Cards */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

/* Responsive design for smaller screens */
@media only screen and (max-width: 768px) {
    .card .icon {
        font-size: 25px;
    }

    .card .number {
        font-size: 20px;
    }

    .page-header {
        font-size: 20px;
    }
}

    </style>
</head>

<body>
    <!-- Wrapper for the entire page -->
    <div id="wrapper">
        <!-- Navbar (Top) -->
        <?php include_once('includes/header.php'); ?>

        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Page Wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <!-- Page Header -->
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
            </div>

            <!-- Main Dashboard Grid for displaying info -->
            <div class="dashboard-grid">
                <!-- Total Pass Card -->
                <div class="card">
                    <?php 
                    // Total Passes
                    $sql = "SELECT ID from tblpass";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $totalpass = $query->rowCount();
                    ?>
                    <div class="icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="number">
                        <?php echo htmlentities($totalpass); ?>
                    </div>
                    <a href="manage-pass.php">Total Pass</a>
                </div>

                <!-- Today's Pass Created Card -->
                <div class="card">
                    <?php 
                    // Today's Pass Generated
                    $sql = "SELECT ID from tblpass where date(PasscreationDate)=CURDATE()";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $today_pass = $query->rowCount();
                    ?>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="number">
                        <?php echo htmlentities($today_pass); ?>
                    </div>
                    <a href="todays-pass.php">Pass Created Today</a>
                </div>

                <!-- Pass Created in Seven Days Card -->
                <div class="card">
                    <?php 
                    // 7 days Pass Generated
                    $sql = "SELECT ID from tblpass where date(PasscreationDate)>=(DATE(NOW()) - INTERVAL 7 DAY)";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $seven_pass = $query->rowCount();
                    ?>
                    <div class="icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="number">
                        <?php echo htmlentities($seven_pass); ?>
                    </div>
                    <a href="last-7days-pass.php">Pass Created in Seven Days</a>
                </div>
            </div>
        </div>
        <!-- End of Page Wrapper -->
    </div>

    <!-- Scripts for functionality -->
    <script src="assets/plugins/jquery-1.10.2.js"></script>
    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/plugins/pace/pace.js"></script>
    <script src="assets/scripts/siminta.js"></script>
</body>

</html>
