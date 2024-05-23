<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $freight_name = $_POST['freight_name'];
    $weight = $_POST['weight'];
    $type = $_POST['type'];
    $route = $_POST['route'];

    $sql = "INSERT INTO Freight_Bookings (user_id, freight_name, weight, type, route) VALUES ('$user_id', '$freight_name', '$weight', '$type', '$route')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Booking added successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Booking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Booking</h2>

    <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="freight_name">Freight Name:</label>
            <input type="text" class="form-control" id="freight_name" name="freight_name" required>
        </div>
        <div class="form-group">
            <label for="weight">Weight:</label>
            <input type="number" step="0.01" class="form-control" id="weight" name="weight" required>
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select class="form-control" id="type" name="type" required>
                <option value="fragile">Fragile</option>
                <option value="non-fragile">Non-Fragile</option>
            </select>
        </div>
        <div class="form-group">
            <label for="route">Route:</label>
            <input type="text" class="form-control" id="route" name="route" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Booking</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
