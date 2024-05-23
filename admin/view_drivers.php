<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle driver deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Drivers WHERE driver_id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Driver deleted successfully!";
    } else {
        $error_message = "Error deleting record: " . $conn->error;
    }
}

// Fetch all drivers
$sql = "SELECT * FROM Drivers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Drivers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Drivers List</h2>

    <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Driver ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
                <th>License No</th>
                <th>Routes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['driver_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['license_no']}</td>
                        <td>{$row['routes']}</td>
                        <td>
                            <a href='edit_driver.php?driver_id={$row['driver_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='view_drivers.php?delete_id={$row['driver_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this driver?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No drivers found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
