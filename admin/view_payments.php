<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all payments and calculate the total amount
$sql = "SELECT *, SUM(amount) AS total_amount FROM Payments";
$result = $conn->query($sql);
$total_amount = 0;
if ($result->num_rows > 0) {
    $total_row = $result->fetch_assoc();
    $total_amount = $total_row['total_amount'];
    // Fetch all rows again to display in the table
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Payments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Payments</h2>

    <button id="downloadExcel" class="btn btn-success mb-3 float-right">Download as Excel</button>

    <div class="table-responsive">
        <table id="paymentsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Booking ID</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Transaction ID</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['payment_id']}</td>
                            <td>{$row['booking_id']}</td>
                            <td>{$row['amount']}</td>
                            <td>{$row['payment_date']}</td>
                            <td>{$row['transaction_id']}</td>
                            <td>{$row['payment_method']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No payments found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <h4>Total Payments: $<?php echo number_format($total_amount, 2); ?></h4>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.getElementById('downloadExcel').addEventListener('click', function () {
    var wb = XLSX.utils.table_to_book(document.getElementById('paymentsTable'), {sheet: "Payments"});
    XLSX.writeFile(wb, 'Payments.xlsx');
});
</script>
</body>
</html>
