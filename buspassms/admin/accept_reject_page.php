<?php
// Connect to database
$conn = mysqli_connect("localhost", "root", " ", "buspassdb");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve applications from database
$applications = array();
$query = "SELECT * FROM applications";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $applications[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];
  if (isset($_POST["accept"])) {
    $query = "UPDATE applications SET status = 'accepted' WHERE id = '$id'";
    mysqli_query($conn, $query);
  } elseif (isset($_POST["reject"])) {
    $query = "UPDATE applications SET status = 'rejected' WHERE id = '$id'";
    mysqli_query($conn, $query);
  }
  // Refresh the page to show updated status
  header("Location: ".$_SERVER['PHP_SELF']);
  exit;
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>
  <h1>Admin Dashboard</h1>
  <table>
    <thead>
      <tr>
        <th>User ID</th>
        <th>User Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($applications as $application) { ?>
      <tr>
        <td><?= $application['id'] ?></td>
        <td><?= $application['name'] ?></td>
        <td><?= $application['email'] ?></td>
        <td><?= $application['status'] ?></td>
        <td>
          <form action="" method="post">
            <input type="hidden" name="id" value="<?= $application['id'] ?>">
            <input type="submit" name="accept" value="Accept">
            <input type="submit" name="reject" value="Reject">
          </form>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</body>
</html>