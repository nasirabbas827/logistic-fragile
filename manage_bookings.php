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

// Handle deletion
if (isset($_GET['delete'])) {
    $booking_id = intval($_GET['delete']);
    $sql = "DELETE FROM Freight_Bookings WHERE booking_id = $booking_id AND user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Booking deleted successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Fetch bookings
$sql = "SELECT * FROM Freight_Bookings WHERE user_id = $user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>My Bookings</h2>

    <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Freight Name</th>
                <th>Weight</th>
                <th>Type</th>
                <th>Route</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $status = $row['status'];
                    echo "<tr>";
                    echo "<td>" . $row['booking_id'] . "</td>";
                    echo "<td>" . $row['freight_name'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['route'] . "</td>";
                    echo "<td>" . $status . "</td>";
                    echo "<td>";

                    if ($status === 'pending') {
                        echo "<a href='edit_booking.php?id=" . $row['booking_id'] . "' class='btn btn-warning btn-sm'>Edit</a> ";
                        echo "<a href='manage_bookings.php?delete=" . $row['booking_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this booking?\");'>Delete</a>";
                    }

                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No bookings found</td></tr>";
            } ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
