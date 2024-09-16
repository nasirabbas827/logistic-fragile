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

// Handle form submission for updating booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = intval($_POST['id']);
    $freight_name = $_POST['freight_name'];
    $weight = $_POST['weight'];
    $type = $_POST['type'];
    $route = $_POST['route'];

    $sql = "UPDATE Freight_Bookings SET freight_name = '$freight_name', weight = '$weight', type = '$type', route = '$route' WHERE booking_id = $booking_id AND user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Booking updated successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Fetch booking details for the form
$booking_id = intval($_GET['id']);
$sql = "SELECT * FROM Freight_Bookings WHERE booking_id = $booking_id AND user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("location: view_bookings.php");
    exit;
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Booking</h2>

    <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="id" value="<?php echo $booking['booking_id']; ?>">
        <div class="form-group">
            <label for="freight_name">Freight Name:</label>
            <input type="text" class="form-control" id="freight_name" name="freight_name" value="<?php echo htmlspecialchars($booking['freight_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="weight">Weight:</label>
            <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($booking['weight']); ?>" required>
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select class="form-control" id="type" name="type" required>
                <option value="fragile" <?php echo $booking['type'] == 'fragile' ? 'selected' : ''; ?>>Fragile</option>
                <option value="non-fragile" <?php echo $booking['type'] == 'non-fragile' ? 'selected' : ''; ?>>Non-Fragile</option>
            </select>
        </div>
        <div class="form-group">
            <label for="route">Route:</label>
            <input type="text" class="form-control" id="route" name="route" value="<?php echo htmlspecialchars($booking['route']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Booking</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
