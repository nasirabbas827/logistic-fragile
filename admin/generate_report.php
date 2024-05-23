<?php
session_start();
include('config.php');
require('../fpdf/fpdf.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$start_date = $end_date = "";
$total_income = $total_expense = $net_profit = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve start and end dates
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Query to fetch income records within the date range
    $income_sql = "SELECT SUM(amount) AS total_income FROM Incomes WHERE income_date BETWEEN '$start_date' AND '$end_date'";
    $income_result = $conn->query($income_sql);
    $income_row = $income_result->fetch_assoc();
    $total_income = $income_row['total_income'];

    // Query to fetch expense records within the date range
    $expense_sql = "SELECT SUM(amount) AS total_expense FROM Expenses WHERE expense_date BETWEEN '$start_date' AND '$end_date'";
    $expense_result = $conn->query($expense_sql);
    $expense_row = $expense_result->fetch_assoc();
    $total_expense = $expense_row['total_expense'];

    // Calculate net profit
    $net_profit = $total_income - $total_expense;
}

// Generate PDF report
if (isset($_POST['generate_pdf'])) {
    // Create PDF object
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Financial Report', 0, 1, 'C');

    // Add report details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Start Date: ' . $start_date, 0, 1);
    $pdf->Cell(0, 10, 'End Date: ' . $end_date, 0, 1);
    $pdf->Cell(0, 10, 'Total Income: $' . $total_income, 0, 1);
    $pdf->Cell(0, 10, 'Total Expense: $' . $total_expense, 0, 1);
    $pdf->Cell(0, 10, 'Net Profit: $' . $net_profit, 0, 1);

    // Output PDF
    $pdf->Output('Financial_Report.pdf', 'D');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Generate Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Generate Report</h2>

    <!-- Report Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>

        <!-- Download as PDF -->
        <button type="submit" class="btn btn-success" name="generate_pdf">Download PDF</button>
        <!-- Download as Excel -->
        <button type="button" class="btn btn-success" id="download_excel">Download Excel</button>
    </form>
    </form>

    <?php if ($start_date && $end_date): ?>
        <!-- Report Results -->
        <div class="mt-5">
            <h3>Report Results</h3>
            <p><strong>Start Date:</strong> <?php echo $start_date; ?></p>
            <p><strong>End Date:</strong> <?php echo $end_date; ?></p>
            <p><strong>Total Income:</strong> $<?php echo $total_income; ?></p>
            <p><strong>Total Expense:</strong> $<?php echo $total_expense; ?></p>
            <p><strong>Net Profit:</strong> $<?php echo $net_profit; ?></p>
        </div>
    <?php endif; ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- JavaScript for exporting data to Excel -->
<script>
    $(document).ready(function () {
        $('#download_excel').click(function () {
            // Create a table with the report data
            var table = '<table><tr><th>Start Date</th><th>End Date</th><th>Total Income</th><th>Total Expense</th><th>Net Profit</th></tr>';
            table += '<tr><td><?php echo $start_date; ?></td><td><?php echo $end_date; ?></td><td><?php echo $total_income; ?></td><td><?php echo $total_expense; ?></td><td><?php echo $net_profit; ?></td></tr></table>';

            // Create a blob with the HTML table
            var blob = new Blob([table], {
                type: 'application/vnd.ms-excel'
            });

            // Create a temporary anchor element
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);

            // Set the file name and trigger the download
            link.download = 'Financial_Report.xls';
            link.click();
        });
    });
</script>
</body>
</html>