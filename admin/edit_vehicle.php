<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$vehicle_id = $_GET['vehicle_id'];

// Fetch vehicle details
$sql = "SELECT * FROM Vehicles WHERE vehicle_id = $vehicle_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $vehicle = $result->fetch_assoc();
} else {
    header("Location: view_vehicles.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $license_no = $_POST['license_no'];
    $registration_date = $_POST['registration_date'];

    $sql = "UPDATE Vehicles SET brand='$brand', model='$model', license_no='$license_no', registration_date='$registration_date' WHERE vehicle_id = $vehicle_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_vehcile.php");
        exit;
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Edit Vehicle</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Vehicle</h2>

    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?vehicle_id=$vehicle_id"; ?>">
        <div class="form-group">
            <label for="brand">Brand:</label>
            <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $vehicle['brand']; ?>" required>
        </div>
        <div class="form-group">
            <label for="model">Model:</label>
            <input type="text" class="form-control" id="model" name="model" value="<?php echo $vehicle['model']; ?>" required>
        </div>
        <div class="form-group">
            <label for="license_no">License No:</label>
            <input type="text" class="form-control" id="license_no" name="license_no" value="<?php echo $vehicle['license_no']; ?>" required>
        </div>
        <div class="form-group">
            <label for="registration_date">Registration Date:</label>
            <input type="date" class="form-control" id="registration_date" name="registration_date" value="<?php echo $vehicle['registration_date']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Vehicle</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
