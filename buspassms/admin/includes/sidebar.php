<?php

error_reporting(0);

include('includes/dbconnection.php');
?>
        <nav class="navbar-default navbar-static-side" role="navigation">
            <!-- sidebar-collapse -->
            <div class="sidebar-collapse">
                <!-- side-menu -->
                <ul class="nav" id="side-menu">
                    <li>
                        <!-- user image section-->
                        <div class="user-section">
                            
                            <div class="user-section-inner">
                                <img src="assets/img/user.jpg" alt="">
                            </div>
                            <div class="user-info">
                                <?php
$aid=$_SESSION['bpmsaid'];
$sql="SELECT AdminName from  tbladmin where ID=:aid";
$query = $dbh -> prepare($sql);
$query->bindParam(':aid',$aid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                                <div><strong><?php  echo $row->AdminName;?></strong></div>
                                <div class="user-text-online">
                                    <span class="user-circle-online btn btn-success btn-circle "></span>&nbsp;Online
                                </div>
                            </div>
                            <?php $cnt=$cnt+1;}} ?>
                        </div>
                        
                        <!--end user image section-->
                    </li>

                    <li class="selected">
                        <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i>Dashboard</a>
                    </li>
                  <!--  <li>
                        <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Category<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="add-category.php">Add Category</a>
                            </li>
                            <li>
                                <a href="manage-category.php">Manage Category</a>
                            </li>
                        </ul>
                         second-level-items 
                    </li> -->
                    <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Passes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <!--<li>
                                <a href="add-pass.php">Add Pass</a>
                            </li>-->
                            <li>
                                <a href="manage-pass.php">Manage Pass</a>
                            </li>
                        </ul>
                        <!-- second-level-items -->
                    </li>
                    <li>
                        <!-- second-level-items -->
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> New pass /renewal pass apply<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="view new pass.php">view new pass apply list</a>
                            </li>
                            <li>
                                <a href="view new renewal pass.php">view new renewal pass list</a>
                            </li>

                        </ul>
                        <!-- second-level-items -->
                    </li>
                    <!--<li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Notification Approval <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="..\admin\notification_button.php">Notification send</a>
                            </li>
                            <li>
                                <a href="unreadenq.php">Unread Enquiry</a>
                            </li>
                        </ul>
                        second-level-items
                    </li>-->
                    <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Pages<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="aboutus.php">About Us</a>
                            </li>
                            <li>
                                <a href="contactus.php">Contact Us</a>
                            </li>
                        </ul>
                        <!-- second-level-items -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Enquiry<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="readenq.php">Read Enquiry</a>
                            </li>
                            <li>
                                <a href="unreadenq.php">Unread Enquiry</a>
                            </li>
                        </ul>
                        <!-- second-level-items -->
                    </li>
                    
                    <li>
                        <a href="search-pass.php"><i class="fa fa-search"></i>  Search<span class="fa arrow"></span></a>
                        
                        </li>
                        <li>
                        <a href="pass-bwdates-report.php"><i class="fa fa-folder"></i>  Report of Pass<span class="fa arrow"></span></a>
                        
                        </li>

                      

                </ul>
                <!-- end side-menu -->
            </div>
            <!-- end sidebar-collapse -->
        </nav>
        <!--<style>
/* Sidebar Styles */
.sidebar-collapse {
    background: #2e3b4e; /* Deep blue background */
    height: 100vh; /* Full height */
    width: 200px; /* Fixed width */
    position: fixed; /* Fixed position */
    top: 0;
    left: 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
    overflow-y: auto; /* Scrollable content */
    z-index: 1000; /* Ensure it stays on top */
    transition: width 0.3s ease; /* Smooth transition for width */
}

/* Side Menu Container */
#side-menu {
    padding: 20px 0;
    margin: 0;
    list-style: none; /* Remove bullet points */
}

/* Main Menu Items */
#side-menu > li {
    border-bottom: 1px solid #3b4a5d; /* Subtle border */
}

#side-menu > li > a {
    color: #e0e0e0; /* Light text color */
    display: flex;
    align-items: center;
    padding: 15px 20px;
    font-size: 15px;
    text-decoration: none;
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s ease, color 0.3s ease;
}

#side-menu > li > a:hover {
    background-color: #1c2a38; /* Darker background on hover */
    color: #ffffff; /* White text on hover */
}

#side-menu > li.selected > a {
    background-color: #007bff; /* Active item background color */
    color: #ffffff; /* Active item text color */
}

/* Icon Styles */
#side-menu > li > a .fa {
    margin-right: 15px; /* Space between icon and text */
    font-size: 20px; /* Slightly larger icon */
    color: #cfd8dc; /* Light color for icons */
}

/* Sub-Menu Styles */
.nav-second-level {
    padding-left: 20px; /* Indent sub-menu items */
    display: none; /* Hide by default */
    background: #34495e; /* Darker background for sub-menu */
    border-radius: 0 0 5px 5px; /* Rounded bottom corners */
}

.nav-second-level li {
    border-bottom: none; /* Remove bottom border */
}

.nav-second-level li a {
    font-size: 14px; /* Smaller font size for sub-menu items */
    color: #aab0b8; /* Lighter color for sub-menu items */
    text-decoration: none; /* Remove underline */
    padding: 10px 20px;
    border-radius: 5px; /* Rounded corners for sub-menu items */
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-second-level li a:hover {
    background-color: #1c2a38; /* Darker background on hover */
    color: #ffffff; /* White text on hover */
}

/* User Section */
.user-section {
    padding: 20px;
    border-bottom: 1px solid #3b4a5d;
    text-align: center;
    background: #2c3e50; /* Slightly darker background for user section */
}

.user-section-inner img {
    width: 90px; /* Slightly larger image */
    height: 90px;
    border-radius: 50%;
    border: 3px solid #ffffff; /* White border around user image */
}

.user-info {
    color: #e0e0e0; /* Light text color */
    margin-top: 10px;
}

.user-text-online {
    font-size: 14px;
    margin-top: 5px;
}

.user-circle-online {
    background-color: #2ecc71; /* Online status indicator color */
    width: 12px;
    height: 12px;
    border-radius: 50%; /* Circle shape */
    display: inline-block; /* Display inline */
}

/* Toggle Sidebar Button Style */
.navbar-toggle {
    background-color: #007bff; /* Toggle button color */
    border: none; /* Remove border */
}

.navbar-toggle .icon-bar {
    background-color: #ffffff; /* Toggle button bars color */
}


        </style>-->
        <!--<script>
            document.querySelectorAll('#side-menu > li > a').forEach(menuLink => {
    menuLink.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
        const subMenu = this.nextElementSibling;
        if (subMenu && subMenu.classList.contains('nav-second-level')) {
            subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block';
        }
    });
});

        </script>-->