<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle vehicle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Vehicles WHERE vehicle_id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Vehicle deleted successfully!";
    } else {
        $error_message = "Error deleting record: " . $conn->error;
    }
}

// Fetch all vehicles
$sql = "SELECT * FROM Vehicles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Vehicles</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Vehicles List</h2>

    <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vehicle ID</th>
                <th>Brand</th>
                <th>Model</th>
                <th>License No</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['vehicle_id']}</td>
                        <td>{$row['brand']}</td>
                        <td>{$row['model']}</td>
                        <td>{$row['license_no']}</td>
                        <td>{$row['registration_date']}</td>
                        <td>
                            <a href='edit_vehicle.php?vehicle_id={$row['vehicle_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='view_vehicles.php?delete_id={$row['vehicle_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this vehicle?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No vehicles found</td></tr>";
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
