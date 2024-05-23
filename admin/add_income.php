<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission to add an income
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'];
    $source = $_POST['source'];

    // Insert new income into the database
    $sql = "INSERT INTO Incomes (description, amount, income_date, source) VALUES ('$description', '$amount', '$income_date', '$source')";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Income added successfully!";
    } else {
        $error_message = "Error adding income: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Add Income</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Income</h2>

    <!-- Success/Error Message -->
    <?php
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    } elseif (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <!-- Form to Add Income -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" id="description" name="description" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="income_date">Income Date:</label>
            <input type="date" class="form-control" id="income_date" name="income_date" required>
        </div>
        <div class="form-group">
            <label for="source">Source:</label>
            <input type="text" class="form-control" id="source" name="source" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Income</button>
        <a class="btn btn-outline-dark" href="view_incomes.php">View Income</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
