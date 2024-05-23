<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

// Fetch user's bookings
$user_id = $_SESSION["id"];
$sql = "SELECT b.*, p.amount AS payment_amount, p.payment_date, p.transaction_id, p.payment_method, p.status AS payment_status,
               v.brand AS vehicle_brand, d.name AS driver_name
        FROM Freight_Bookings b
        LEFT JOIN Payments p ON b.booking_id = p.booking_id
        LEFT JOIN Vehicles v ON b.vehicle_id = v.vehicle_id
        LEFT JOIN Drivers d ON b.driver_id = d.driver_id
        WHERE b.user_id = $user_id";
$result = $conn->query($sql);

// Handle form submission for payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['make_payment'])) {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $transaction_id = $_POST['transaction_id'];
    $payment_method = $_POST['payment_method'];
    $payment_status = 'completed';

    $sql = "INSERT INTO Payments (booking_id, amount, transaction_id, payment_method, status) 
            VALUES ('$booking_id', '$amount', '$transaction_id', '$payment_method', '$payment_status')";

    if ($conn->query($sql) === TRUE) {
        $update_booking_sql = "UPDATE Freight_Bookings SET status='in_transit' WHERE booking_id='$booking_id'";
        $conn->query($update_booking_sql);
        $success_message = "Payment successful!";
    } else {
        $error_message = "Error processing payment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>My Bookings</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Freight Name</th>
                    <th>Weight</th>
                    <th>Type</th>
                    <th>Route</th>
                    <th>Status</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Shipment Fee</th>
                    <th>Total Distance</th>
                    <th>Current Location</th>
                    <th>Estimated Arrival</th>
                    <th>Payment Amount</th>
                    <th>Payment Date</th>
                    <th>Transaction ID</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['booking_id']}</td>
                            <td>{$row['freight_name']}</td>
                            <td>{$row['weight']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['route']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['vehicle_brand']}</td>
                            <td>{$row['driver_name']}</td>
                            <td>{$row['shipment_fee']}</td>
                            <td>{$row['total_distance']}</td>
                            <td>{$row['current_location']}</td>
                            <td>{$row['estimated_arrival']}</td>
                            <td>{$row['payment_amount']}</td>
                            <td>{$row['payment_date']}</td>
                            <td>{$row['transaction_id']}</td>
                            <td>{$row['payment_method']}</td>
                            <td>{$row['payment_status']}</td>
                            <td>";
                            
                        // If status is 'approved' and payment is not completed, show Make Payment button
                        if ($row['status'] == 'approved' && $row['payment_status'] != 'completed') {
                            echo "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#paymentModal' 
                                    data-booking-id='{$row['booking_id']}' data-amount='{$row['shipment_fee']}'>Make Payment</button>";
                        }
                        
                        echo "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='18'>No bookings found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="booking_id" name="booking_id">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" readonly>
                    </div>
                    <div class="form-group">
                        <label for="transaction_id">Transaction ID:</label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="online">Online</option>
                            <option value="cash on delivery">Cash on Delivery</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="make_payment" class="btn btn-primary">Make Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$('#paymentModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var booking_id = button.data('booking-id');
    var amount = button.data('amount');

    var modal = $(this);
    modal.find('#booking_id').val(booking_id);
    modal.find('#amount').val(amount);
});
</script>
</body>
</html>
