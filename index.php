<!DOCTYPE html>
<html>
<head>
    <title>Logistics for Fragile Freights</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .jumbotron {
            height: 500px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
include('config.php');



// Fetch drivers
$drivers_query = "SELECT * FROM Drivers";
$drivers_result = $conn->query($drivers_query);

// Fetch vehicles
$vehicles_query = "SELECT * FROM Vehicles";
$vehicles_result = $conn->query($vehicles_query);
?>

<div class="jumbotron text-center">
    <h1>Welcome to Logistics for Fragile Freights</h1>
    <p>Discover Your Perfect Logistics Solutions</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login to Explore</a>
</div>

<div class="container mt-5">
    <h2>Our Drivers</h2>
    <div class="row">
        <!-- Drivers Cards -->
        <?php
        if ($drivers_result->num_rows > 0) {
            while ($driver = $drivers_result->fetch_assoc()) {
                echo "<div class='col-md-4'>
                        <div class='card mb-3'>
                            <div class='card-body'>
                                <h5 class='card-title'>Driver: {$driver['name']}</h5>
                                <p class='card-text'>Age: {$driver['age']}</p>
                                <p class='card-text'>Contact: {$driver['contact']}</p>
                                <p class='card-text'>Email: {$driver['email']}</p>
                                <p class='card-text'>License No: {$driver['license_no']}</p>
                                <p class='card-text'>Routes: {$driver['routes']}</p>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "<div class='col-md-12'>
                    <div class='alert alert-info' role='alert'>
                        No drivers found.
                    </div>
                </div>";
        }
        ?>
    </div>
<h2>Vehciles</h2>
    <div class="row">
        <!-- Vehicles Cards -->
        <?php
        if ($vehicles_result->num_rows > 0) {
            while ($vehicle = $vehicles_result->fetch_assoc()) {
                echo "<div class='col-md-4'>
                        <div class='card mb-3'>
                            <div class='card-body'>
                                <h5 class='card-title'>Vehicle: {$vehicle['brand']} {$vehicle['model']}</h5>
                                <p class='card-text'>License No: {$vehicle['license_no']}</p>
                                <p class='card-text'>Registration Date: {$vehicle['registration_date']}</p>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "<div class='col-md-12'>
                    <div class='alert alert-info' role='alert'>
                        No vehicles found.
                    </div>
                </div>";
        }
        ?>
    </div>
</div>

<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 Logistics for Fragile Freights. All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
