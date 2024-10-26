<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 15px;
        }
        .header .navbar-nav a, .footer a {
            color: #ffffff;
            text-decoration: none;
        }
        .header .navbar-nav a:hover, .footer a:hover {
            text-decoration: underline;
        }
        .header .dropdown-toggle {
            color: #000000; /* Set the color of the user's name to black */
        }
        .header .dropdown-menu {
            background-color: #ffffff; /* Background color of the dropdown menu */
        }
        .header .dropdown-menu .dropdown-item {
            color: #000000; /* Text color of dropdown items */
        }
        .header .dropdown-menu .dropdown-item:hover {
            background-color: #f1f1f1; /* Light gray background for hover effect */
            color: #000000; /* Ensure text color remains black on hover */
        }
        .dashboard-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .dashboard-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .dashboard-container a {
            display: block;
            margin: 10px 0;
            padding: 15px;
            text-decoration: none;
            color: #007bff;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
        }
        .dashboard-container a:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .dashboard-container a.active {
            background-color: #007bff;
            color: #ffffff;
        }
        .footer {
            background-color: #007bff;
            color: #ffffff;
            padding: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light header">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="..\user\images\e76de47f621d84adbab3266e3239baee1625460898.png" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%;">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="dashboard-container">
        <h1 class="text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <div class="d-grid gap-2">
            <a href="../user/applyform.php" class="btn btn-outline-primary">Apply for New Pass</a>
            <a href="../user/view-pass.php" class="btn btn-outline-primary">View Pass</a>
            <a href="download-pass.php" class="btn btn-outline-primary">Download Pass</a>
            <a href="renewal.php" class="btn btn-outline-primary">Renew Pass</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2024 Your Website. All Rights Reserved.
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>
