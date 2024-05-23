<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete income
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Incomes WHERE income_id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Income deleted successfully!";
    } else {
        $error_message = "Error deleting income: " . $conn->error;
    }
}

// Fetch all incomes or filter by date range
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (!empty($start_date) && !empty($end_date)) {
    $sql = "SELECT * FROM Incomes WHERE income_date BETWEEN '$start_date' AND '$end_date'";
} else {
    $sql = "SELECT * FROM Incomes";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Incomes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-3">
    <h4>Search by Date Range</h4>
    <form method="GET">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="form-group col-md-4">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-primary m-4">Search</button>
            </div>
        </div>
    </form>
</div>

<div class="container mt-3">
    <h2>View Incomes</h2>

    <!-- Success/Error Message -->
    <?php
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    } elseif (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <!-- Incomes Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Income ID</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Income Date</th>
                    <th>Source</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['income_id']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['amount']}</td>
                            <td>{$row['income_date']}</td>
                            <td>{$row['source']}</td>
                            <td>
                                <a href='edit_income.php?id={$row['income_id']}' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='?delete_id={$row['income_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this income?\");'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No incomes found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
