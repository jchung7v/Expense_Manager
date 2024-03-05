<?php 
session_start();
include("../inc_header.php");
include("../inc_header_main.php");
?>

<?php
require_once '../backend.php'; // Make sure this path is correct
$database = new ExpenseDatabase();
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // Default to current year if not provided
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    <?php echo $database->generatePieChartJS($year); ?>
</script>

<div id="piechart" style="width: 700px; height: 400px;"></div>
<?php echo $database->generateReportTable($year); ?>

<?php include("../inc_footer.php"); ?>

