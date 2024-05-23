<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$driver_id = $_GET['driver_id'];

// Fetch driver details
$sql = "SELECT * FROM Drivers WHERE driver_id = $driver_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $driver = $result->fetch_assoc();
} else {
    header("Location: view_drivers.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $license_no = $_POST['license_no'];
    $routes = $_POST['routes'];

    $sql = "UPDATE Drivers SET name='$name', age='$age', address='$address', contact='$contact', email='$email', license_no='$license_no', routes='$routes' WHERE driver_id = $driver_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_drivers.php");
        exit;
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Edit Driver</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Edit Driver</h2>

    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?driver_id=$driver_id"; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $driver['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" class="form-control" id="age" name="age" value="<?php echo $driver['age']; ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $driver['address']; ?>" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact:</label>
            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $driver['contact']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $driver['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="license_no">License No:</label>
            <input type="text" class="form-control" id="license_no" name="license_no" value="<?php echo $driver['license_no']; ?>" required>
        </div>
        <div class="form-group">
            <label for="routes">Routes:</label>
            <textarea class="form-control" id="routes" name="routes" required><?php echo $driver['routes']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Driver</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
