<!DOCTYPE html>
<html>
<head>
    <title>Bus Pass Management System | View New Pass</title>
    <!-- Core CSS - Include with every page -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />
    <!-- Page-Level CSS -->
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            table-layout: auto;
        }
        th, td {
            white-space: nowrap;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Navbar top -->
        <?php include_once('includes/header.php'); ?>
        <!-- End navbar top -->

        <!-- Navbar side -->
        <?php include_once('includes/sidebar.php'); ?>
        <!-- End navbar side -->

        <!-- Page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <!-- Page header -->
                <div class="col-lg-12">
                    <h1 class="page-header">New Pass Apply List</h1>
                </div>
                <!-- End page header -->
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>SI NO</th>
                                            <th>Name</th>
                                            <th>GENDER</th>
                                            <th>CONTACT NUMBER</th>
                                            <th>DOB</th>
                                            <th>EMAIL</th>
                                            <th>AADHAAR CARD</th>
                                            <!--<th>STUDENT ID CARD</th>-->
                                            <th>FROM DATE</th>
                                            <th>TO DATE</th>
                                            <th>SOURCE</th>
                                            <th>DESTINATION</th>
                                            <th>AMOUNT</th>
                                            <th>ADDRESS</th>
                                            <th>CREATION DATE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Database connection
                                        $con = mysqli_connect("localhost", "root", "", "buspassdb");
                                        if (!$con) {
                                            die("Connection failed: " . mysqli_connect_error());
                                        }

                                        // Updated SQL query to fetch required fields
                                        $sql = "SELECT id, name, gender, Contactnumber, DOB, email, fromdate, Todate, source, destination, amount, address, creationdate, aadhar_card, status FROM tblapplynewform";
                                        $query = $con->query($sql);

                                        $serialNumber = 1;

                                        while ($row = $query->fetch_assoc()) {
                                            $status = $row['status'];
                                        ?>
                                            <tr id="row<?php echo $row['id']; ?>">
                                                <td><?php echo $serialNumber++; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['gender']; ?></td>
                                                <td><?php echo $row['Contactnumber']; ?></td>
                                                <td><?php echo $row['DOB']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['aadhar_card']; ?></td>
                                                <!--<td>
                                                    <a href="uploads/student_id_cards/<?php echo $row['student_id_card']; ?>" target="_blank">
                                                        View Student ID
                                                    </a>
                                                </td> -->
                                                <td><?php echo $row['fromdate']; ?></td>
                                                <td><?php echo $row['Todate']; ?></td>
                                                <td><?php echo $row['source']; ?></td>
                                                <td><?php echo $row['destination']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <td><?php echo $row['address']; ?></td>
                                                <td><?php echo $row['creationdate']; ?></td>
                                                <td>
                                                    <?php if ($status == 'Accepted' || $status == 'Rejected') { ?>
                                                        <span class="label label-<?php echo ($status == 'Accepted') ? 'success' : 'danger'; ?>">
                                                            <?php echo $status; ?>
                                                        </span>
                                                    <?php } else { ?>
                                                        <button class="btn btn-success" onclick="processPass('accept', <?php echo $row['id']; ?>)">ACCEPT</button>
                                                        <button class="btn btn-danger" onclick="toggleRejectForm(<?php echo $row['id']; ?>)">REJECT</button>
                                                        <div id="rejectForm<?php echo $row['id']; ?>" style="display:none;">
                                                            <select name="rejection_reason" id="rejection_reason<?php echo $row['id']; ?>" required>
                                                                <option value="Invalid documents">Invalid documents</option>
                                                                <option value="Incomplete information">Not satisfied eligibility</option>
                                                                <option value="Fake identity">Fake identity</option>
                                                                <option value="Other">Invalid Date</option>
                                                            </select>
                                                            <button class="btn btn-danger" onclick="processPass('reject', <?php echo $row['id']; ?>)">Submit</button>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Tables -->
                </div>
            </div>
        </div>
        <!-- End page-wrapper -->
    </div>
    <!-- End wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="assets/plugins/jquery-1.10.2.js"></script>
    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/plugins/pace/pace.js"></script>
    <script src="assets/scripts/siminta.js"></script>
    <!-- Page-Level Plugin Scripts -->
    <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>

    <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });

    function toggleRejectForm(id) {
        var form = document.getElementById('rejectForm' + id);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function processPass(action, id) {
        var rejectionReason = action === 'reject' ? document.getElementById('rejection_reason' + id).value : null;

        $.ajax({
            url: 'process-pass.php',
            type: 'POST',
            data: {
                action: action,
                id: id,
                rejection_reason: rejectionReason
            },
            success: function (response) {
                var result = JSON.parse(response);
                alert(result.message);

                if (result.status === 'success') {
                    var status = action === 'accept' ? 'Accepted' : 'Rejected';
                    var statusClass = action === 'accept' ? 'success' : 'danger';

                    // Update the row status
                    $('#row' + id + ' td:last').html('<span class="label label-' + statusClass + '">' + status + '</span>');

                    // Remove action buttons
                    $('#row' + id + ' button').remove();

                    // Optionally fade out the row
                    $('#row' + id).fadeOut();
                }
            },
            error: function () {
                alert('An error occurred while processing your request.');
            }
        });
    }
    </script>
</body>
</html>
