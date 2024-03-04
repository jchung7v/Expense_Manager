<?php

require_once 'backend.php'; // Adjust the path as necessary

// Instantiate the ExpenseDatabase
$database = new ExpenseDatabase();

// Assuming year is passed via GET request. Validate this in real applications.
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // Default to current year if not provided

// Generate the report
$report = $database->generateReport($year);

// Display the report
echo "<h2>Expense Report for Year: $year</h2>";
echo "<table border='1'><tr><th>Category</th><th>Amount</th></tr>";
$total = 0;
foreach ($report as $category => $amount) {
    echo "<tr><td>" . htmlspecialchars($category) . "</td><td>" . number_format($amount, 2) . "</td></tr>";
    $total += $amount;
}
echo "<tr><td><strong>Total</strong></td><td><strong>" . number_format($total, 2) . "</strong></td></tr>";
echo "</table>";

// Check if total is 0, indicating no data or potential issue
if ($total == 0) {
    echo "<p>No transaction data available for the year $year, or all transactions are categorized as 'Other'.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Report</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Category', 'Amount'],
          <?php
          foreach ($report as $category => $amount) {
              echo "['" . addslashes($category) . "', " . $amount . "],";
          }
          ?>
        ]);

        var options = {
          title: 'Expenses by Category',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
</head>
<body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>
