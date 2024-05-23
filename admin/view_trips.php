<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete request
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $trip_id = $_GET['id'];

    // Delete trip from the database
    $sql_delete = "DELETE FROM Trips WHERE trip_id = $trip_id";

    if ($conn->query($sql_delete) === TRUE) {
        $delete_message = "Trip deleted successfully.";
    } else {
        $error_message = "Error deleting trip: " . $conn->error;
    }
}

// Fetch all trips with vehicle and driver names
$sql = "SELECT Trips.*, Vehicles.brand AS vehicle_brand, Vehicles.model AS vehicle_model, Drivers.name AS driver_name FROM Trips 
        INNER JOIN Vehicles ON Trips.vehicle_id = Vehicles.vehicle_id 
        INNER JOIN Drivers ON Trips.driver_id = Drivers.driver_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Trips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Trips</h2>

    <?php if (isset($delete_message)) { ?>
        <div class="alert alert-success"><?php echo $delete_message; ?></div>
    <?php } ?>

    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php } ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Trip ID</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Start Point</th>
                    <th>Destination</th>
                    <th>Distance (km)</th>
                    <th>Charges</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['trip_id']}</td>
                            <td>{$row['vehicle_brand']} {$row['vehicle_model']}</td>
                            <td>{$row['driver_name']}</td>
                            <td>{$row['start_point']}</td>
                            <td>{$row['destination']}</td>
                            <td>{$row['distance']}</td>
                            <td>{$row['charges']}</td>
                            <td>
                                <a href='edit_trip.php?id={$row['trip_id']}' class='btn btn-primary'>Edit</a>
                                <a href='view_trips.php?id={$row['trip_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this trip?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No trips found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
