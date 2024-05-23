<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch the income details based on the provided ID
if (isset($_GET['id'])) {
    $income_id = $_GET['id'];
    $sql = "SELECT * FROM Incomes WHERE income_id='$income_id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        header("Location: view_incomes.php");
        exit;
    }
}

// Handle form submission to update income details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'];
    $source = $_POST['source'];

    // Update income details in the database
    $sql = "UPDATE Incomes SET description='$description', amount='$amount', income_date='$income_date', source='$source' WHERE income_id='$income_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Income updated successfully!";
    } else {
        $error_message = "Error updating income: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Edit Income</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Income</h2>

    <!-- Success/Error Message -->
    <?php
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    } elseif (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <!-- Form to Edit Income -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=" . $income_id); ?>">
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $row['description']; ?>" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo $row['amount']; ?>" required>
        </div>
        <div class="form-group">
            <label for="income_date">Income Date:</label>
            <input type="date" class="form-control" id="income_date" name="income_date" value="<?php echo $row['income_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="source">Source:</label>
            <input type="text" class="form-control" id="source" name="source" value="<?php echo $row['source']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Income</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
