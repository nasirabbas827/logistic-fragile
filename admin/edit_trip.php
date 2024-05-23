<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trip_id = $_POST['trip_id'];
    $start_point = $_POST['start_point'];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $charges = $_POST['charges'];

    // Update trip in the database
    $sql_update = "UPDATE Trips SET start_point='$start_point', destination='$destination', distance='$distance', charges='$charges' WHERE trip_id=$trip_id";

    if ($conn->query($sql_update) === TRUE) {
        $success_message = "Trip updated successfully.";
        header("Location: view_trips.php");
        exit;
    } else {
        $error_message = "Error updating trip: " . $conn->error;
    }
}

// Fetch trip details
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $trip_id = $_GET['id'];

    $sql_trip = "SELECT * FROM Trips WHERE trip_id = $trip_id";
    $result_trip = $conn->query($sql_trip);

    if ($result_trip->num_rows == 1) {
        $row = $result_trip->fetch_assoc();
    } else {
        $error_message = "Trip not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Trip</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Trip</h2>

    <?php if (isset($success_message)) { ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php } ?>

    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="hidden" name="trip_id" value="<?php echo $trip_id; ?>">
        <div class="form-group">
            <label for="start_point">Start Point:</label>
            <input type="text" class="form-control" id="start_point" name="start_point" value="<?php echo $row['start_point']; ?>" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" class="form-control" id="destination" name="destination" value="<?php echo $row['destination']; ?>" required>
        </div>
        <div class="form-group">
            <label for="distance">Distance (in km):</label>
            <input type="number" step="0.01" class="form-control" id="distance" name="distance" value="<?php echo $row['distance']; ?>" required>
        </div>
        <div class="form-group">
            <label for="charges">Charges:</label>
            <input type="number" step="0.01" class="form-control" id="charges" name="charges" value="<?php echo $row['charges']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Trip</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
