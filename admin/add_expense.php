<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission to add new expense
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $category = $_POST['category'];

    $sql = "INSERT INTO Expenses (description, amount, expense_date, category) VALUES ('$description', '$amount', '$expense_date', '$category')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Expense added successfully!";
    } else {
        $error_message = "Error adding expense: " . $conn->error;
    }
}

// Fetch all expenses
$sql = "SELECT * FROM Expenses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Manage Expenses</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Manage Expenses</h2>

    <!-- Success/Error Message -->
    <?php
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    } elseif (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <!-- Form to Add Expense -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="mb-4">
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" id="description" name="description" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="expense_date">Expense Date:</label>
            <input type="date" class="form-control" id="expense_date" name="expense_date" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" class="form-control" id="category" name="category" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Expense</button>
        <a class="btn btn-outline-dark" href="view_expenses.php">View Expenses</a>
    </form>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
