<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$booking_id = "";
$booking_details = null;
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['track_booking'])) {
    $booking_id = $_POST['booking_id'];
    
    // Validate booking ID
    if (!empty($booking_id)) {
        // Fetch booking details
        $sql = "SELECT b.*, p.amount AS payment_amount, p.payment_date, p.transaction_id, p.payment_method, p.status AS payment_status,
                       v.brand AS vehicle_brand, d.name AS driver_name
                FROM Freight_Bookings b
                LEFT JOIN Payments p ON b.booking_id = p.booking_id
                LEFT JOIN Vehicles v ON b.vehicle_id = v.vehicle_id
                LEFT JOIN Drivers d ON b.driver_id = d.driver_id
                WHERE b.booking_id = '$booking_id' AND b.user_id = {$_SESSION['id']}";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $booking_details = $result->fetch_assoc();
        } else {
            $error_message = "No booking found with ID: $booking_id";
        }
    } else {
        $error_message = "Please enter a booking ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Booking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .high-priority {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Track Your Booking</h2>

    <!-- Track Booking Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="booking_id">Enter Booking ID:</label>
            <input type="text" class="form-control" id="booking_id" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>" required>
        </div>
        <button type="submit" name="track_booking" class="btn btn-primary">Track Booking</button>
    </form>

    <?php if ($error_message): ?>
        <div class="alert alert-danger mt-3">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($booking_details): ?>
        <div class="mt-3 mb-5">
            <ul class="list-group">
                <li class="list-group-item"><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_details['booking_id']); ?></li>
                <li class="list-group-item"><strong>Freight Name:</strong> <?php echo htmlspecialchars($booking_details['freight_name']); ?></li>
                <li class="list-group-item"><strong>Weight:</strong> <?php echo htmlspecialchars($booking_details['weight']); ?></li>
                <li class="list-group-item"><strong>Type:</strong> <?php echo htmlspecialchars($booking_details['type']); ?></li>
                <li class="list-group-item"><strong>Route:</strong> <?php echo htmlspecialchars($booking_details['route']); ?></li>
                <li class="list-group-item high-priority"><strong>Status:</strong> <?php echo htmlspecialchars($booking_details['status']); ?></li>
                <li class="list-group-item"><strong>Vehicle:</strong> <?php echo htmlspecialchars($booking_details['vehicle_brand']); ?></li>
                <li class="list-group-item"><strong>Driver:</strong> <?php echo htmlspecialchars($booking_details['driver_name']); ?></li>
                <li class="list-group-item"><strong>Shipment Fee:</strong> <?php echo htmlspecialchars($booking_details['shipment_fee']); ?></li>
                <li class="list-group-item"><strong>Total Distance:</strong> <?php echo htmlspecialchars($booking_details['total_distance']); ?></li>
                <li class="list-group-item"><strong>Current Location:</strong> <?php echo htmlspecialchars($booking_details['current_location']); ?></li>
                <li class="list-group-item high-priority"><strong>Estimated Arrival:</strong> <?php echo htmlspecialchars($booking_details['estimated_arrival']); ?></li>
                <li class="list-group-item"><strong>Payment Amount:</strong> <?php echo htmlspecialchars($booking_details['payment_amount']); ?></li>
                <li class="list-group-item"><strong>Payment Date:</strong> <?php echo htmlspecialchars($booking_details['payment_date']); ?></li>
                <li class="list-group-item"><strong>Transaction ID:</strong> <?php echo htmlspecialchars($booking_details['transaction_id']); ?></li>
                <li class="list-group-item"><strong>Payment Method:</strong> <?php echo htmlspecialchars($booking_details['payment_method']); ?></li>
                <li class="list-group-item"><strong>Payment Status:</strong> <?php echo htmlspecialchars($booking_details['payment_status']); ?></li>
            </ul>
        </div>
    <?php endif; ?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
