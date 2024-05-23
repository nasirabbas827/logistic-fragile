<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch expense details if ID is provided
if (isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    $sql = "SELECT * FROM Expenses WHERE expense_id='$expense_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $description = $row['description'];
        $amount = $row['amount'];
        $expense_date = $row['expense_date'];
        $category = $row['category'];
    } else {
        // Redirect if expense not found
        header("Location: view_expenses.php");
        exit;
    }
} else {
    // Redirect if ID not provided
    header("Location: view_expenses.php");
    exit;
}

// Handle form submission to update expense
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $category = $_POST['category'];
    $sql = "UPDATE Expenses SET description='$description', amount='$amount', expense_date='$expense_date', category='$category' WHERE expense_id='$expense_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Expense updated successfully!";
    } else {
        $error_message = "Error updating expense: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Edit Expense</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Expense</h2>

    <!-- Success/Error Message -->
    <?php
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    } elseif (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <!-- Edit Expense Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=$expense_id"); ?>" class="mb-4">
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>" required>
        </div>
        <div class="form-group">
            <label for="expense_date">Expense Date:</label>
            <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?php echo $expense_date; ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo $category; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Expense</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
