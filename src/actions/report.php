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

<style>
    /* Remove default margins and set a background color for the whole page */
    body {
        margin: 0;
        padding: 0;
        /* Set the background color to your preference or keep it default */
        background-color: #121212; /* Example for dark mode background */
    }
    
    /* Center the pie chart div and remove specific background */
    #piechart {
        width: 700px;
        height: 400px;
        margin: auto;
        background-color: transparent; /* Ensure no background for the chart container */
    }
</style>

<div id="piechart"></div> <!-- Removed inline styles for cleaner separation of concerns -->
<?php echo $database->generateReportTable($year); ?>

<?php include("../inc_footer.php"); ?>
