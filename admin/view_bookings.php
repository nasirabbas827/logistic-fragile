<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all bookings with vehicle, driver, and user details
$sql = "SELECT fb.booking_id, fb.user_id, u.username, fb.freight_name, fb.weight, fb.type, fb.route, fb.status, 
               fb.vehicle_id, v.brand AS vehicle_brand, fb.driver_id, d.name AS driver_name,
               fb.shipment_fee, fb.total_distance, fb.current_location, fb.estimated_arrival
        FROM Freight_Bookings fb
        LEFT JOIN Vehicles v ON fb.vehicle_id = v.vehicle_id
        LEFT JOIN Drivers d ON fb.driver_id = d.driver_id
        LEFT JOIN Users u ON fb.user_id = u.id";
$result = $conn->query($sql);

// Fetch all vehicles
$vehicles_sql = "SELECT vehicle_id, brand FROM Vehicles";
$vehicles_result = $conn->query($vehicles_sql);

// Fetch all drivers
$drivers_sql = "SELECT driver_id, name FROM Drivers";
$drivers_result = $conn->query($drivers_sql);

// Handle form submission for updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    $shipment_fee = $_POST['shipment_fee'];
    $total_distance = $_POST['total_distance'];
    $current_location = $_POST['current_location'];
    $estimated_arrival = $_POST['estimated_arrival'];

    $sql = "UPDATE Freight_Bookings SET status='$status', vehicle_id='$vehicle_id', driver_id='$driver_id', shipment_fee='$shipment_fee', total_distance='$total_distance', current_location='$current_location', estimated_arrival='$estimated_arrival' WHERE booking_id='$booking_id'";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Booking updated successfully!";
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Freight Bookings List</h2>
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Supplier Name</th>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['username']}</td>
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
                        <td>
                            <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal' 
                                    data-id='{$row['booking_id']}' data-status='{$row['status']}' 
                                    data-vehicle-id='{$row['vehicle_id']}' data-driver-id='{$row['driver_id']}' 
                                    data-shipment-fee='{$row['shipment_fee']}' data-total-distance='{$row['total_distance']}' 
                                    data-current-location='{$row['current_location']}' data-estimated-arrival='{$row['estimated_arrival']}'>Edit</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='14'>No bookings found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="booking_id" name="booking_id">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="disapproved">Disapproved</option>
                            <option value="in_transit">In Transit</option>
                            <option value="delivered">Delivered</option>
                            <option value="delayed">Delayed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_id">Vehicle:</label>
                        <select class="form-control" id="vehicle_id" name="vehicle_id">
                            <?php
                            if ($vehicles_result->num_rows > 0) {
                                while($vehicle = $vehicles_result->fetch_assoc()) {
                                    echo "<option value='{$vehicle['vehicle_id']}'>{$vehicle['brand']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="driver_id">Driver:</label>
                        <select class="form-control" id="driver_id" name="driver_id">
                            <?php
                            if ($drivers_result->num_rows > 0) {
                                while($driver = $drivers_result->fetch_assoc()) {
                                    echo "<option value='{$driver['driver_id']}'>{$driver['name']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shipment_fee">Shipment Fee:</label>
                        <input type="number" step="0.01" class="form-control" id="shipment_fee" name="shipment_fee">
                    </div>
                    <div class="form-group">
                        <label for="total_distance">Total Distance:</label>
                        <input type="number" step="0.01" class="form-control" id="total_distance" name="total_distance">
                    </div>
                    <div class="form-group">
                        <label for="current_location">Current Location:</label>
                        <input type="text" class="form-control" id="current_location" name="current_location">
                    </div>
                    <div class="form-group">
                        <label for="estimated_arrival">Estimated Arrival:</label>
                        <input type="datetime-local" class="form-control" id="estimated_arrival" name="estimated_arrival">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var booking_id = button.data('id');
    var status = button.data('status');
    var vehicle_id = button.data('vehicle_id');
    var driver_id = button.data('driver_id');
    var shipment_fee = button.data('shipment_fee');
    var total_distance = button.data('total_distance');
    var current_location = button.data('current_location');
    var estimated_arrival = button.data('estimated_arrival');

    var modal = $(this);
    modal.find('#booking_id').val(booking_id);
    modal.find('#status').val(status);
    modal.find('#vehicle_id').val(vehicle_id);
    modal.find('#driver_id').val(driver_id);
    modal.find('#shipment_fee').val(shipment_fee);
    modal.find('#total_distance').val(total_distance);
    modal.find('#current_location').val(current_location);
    modal.find('#estimated_arrival').val(estimated_arrival);
});
</script>
</body>
</html>
