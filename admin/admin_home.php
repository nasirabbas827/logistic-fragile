<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch data for dashboard
$totalUsers = $totalDrivers = $totalBookings = $totalVehicles = $totalPayments = 0;

// Replace with your SQL queries to fetch data from respective tables
// Example queries:
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$totalDrivers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM drivers"))['total'];
$totalBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM freight_bookings"))['total'];
$totalVehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles"))['total'];
$totalPayments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments"))['total'];
$totalQueriesForReply = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages WHERE reply_text IS NULL"))['total'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <div class="row mt-4">
        <!-- Total Users Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>
        <!-- Total Drivers Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Drivers</h5>
                    <p class="card-text"><?php echo $totalDrivers; ?></p>
                </div>
            </div>
        </div>
        <!-- Total Bookings Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text"><?php echo $totalBookings; ?></p>
                </div>
            </div>
        </div>
        <!-- Total Vehicles Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Vehicles</h5>
                    <p class="card-text"><?php echo $totalVehicles; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <!-- Total Payments Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Payments</h5>
                    <p class="card-text"><?php echo $totalPayments; ?></p>
                </div>
            </div>
        </div>
        <!-- Total Queries for Reply Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Queries for Reply</h5>
                    <p class="card-text"><?php echo $totalQueriesForReply; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
