<?php

require_once '../backend.php'; // Make sure this path is correct

$database = new ExpenseDatabase();
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // Default to current year if not provided

?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Report</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        <?php echo $database->generatePieChartJS($year); ?>
    </script>
</head>
<body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
    <?php echo $database->generateReportTable($year); ?>
</body>
</html>
