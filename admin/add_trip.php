<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch vehicles for dropdown
$sql_vehicles = "SELECT * FROM Vehicles";
$result_vehicles = $conn->query($sql_vehicles);

// Fetch drivers for dropdown
$sql_drivers = "SELECT * FROM Drivers";
$result_drivers = $conn->query($sql_drivers);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    $start_point = $_POST['start_point'];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $charges = $_POST['charges'];

    // Insert trip into database
    $sql_insert = "INSERT INTO Trips (vehicle_id, driver_id, start_point, destination, distance, charges) 
                   VALUES ('$vehicle_id', '$driver_id', '$start_point', '$destination', '$distance', '$charges')";

    if ($conn->query($sql_insert) === TRUE) {
        $success_message = "Trip added successfully.";
    } else {
        $error_message = "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Trip</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Add Trip</h2>

    <?php if (isset($success_message)) { ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php } ?>

    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="vehicle_id">Select Vehicle:</label>
            <select class="form-control" id="vehicle_id" name="vehicle_id" required>
                <option value="">Select Vehicle</option>
                <?php
                if ($result_vehicles->num_rows > 0) {
                    while($row = $result_vehicles->fetch_assoc()) {
                        echo "<option value='{$row['vehicle_id']}'>{$row['brand']} {$row['model']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="driver_id">Select Driver:</label>
            <select class="form-control" id="driver_id" name="driver_id" required>
                <option value="">Select Driver</option>
                <?php
                if ($result_drivers->num_rows > 0) {
                    while($row = $result_drivers->fetch_assoc()) {
                        echo "<option value='{$row['driver_id']}'>{$row['name']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_point">Start Point:</label>
            <input type="text" class="form-control" id="start_point" name="start_point" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" class="form-control" id="destination" name="destination" required>
        </div>
        <div class="form-group">
            <label for="distance">Distance (in km):</label>
            <input type="number" step="0.01" class="form-control" id="distance" name="distance" required>
        </div>
        <div class="form-group">
            <label for="charges">Charges:</label>
            <input type="number" step="0.01" class="form-control" id="charges" name="charges" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Trip</button>
        <a class="btn btn-outline-dark" href="view_trips.php">View Trips</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
