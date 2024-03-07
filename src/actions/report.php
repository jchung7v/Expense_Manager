<?php 
session_start();
include("../inc_header.php");
include("../inc_header_main.php");
require_once '../backend.php';

$database = new ExpenseDatabase();
$year = isset($_GET['year']) ? (int)$_GET['year'] :2023;

// Determine if the yearSelectionForm should be displayed
$displayForm = !isset($_GET['year']);
?>

<h3>Report</h3>

<?php if ($displayForm): ?>
<div id="yearSelectionForm" style="position:fixed; left:50%; top:50%; transform:translate(-50%, -50%); background-color:#333; color: #fff; padding:20px; border-radius:10px; z-index: 1000; box-shadow: 0 0 15px rgba(0,0,0,0.5);">
    <form action="" method="GET"> <!-- Form submits to the same page -->
        <label for="year" style="color: #fff;">Select a year:</label>
        <select name="year" id="year" style="color: #333; background-color: #fff; padding: 4px; border-radius: 5px; margin-right: 10px;">
            <?php
            $currentYear = date('Y');
            for ($y = $currentYear; $y >= 1900; $y--) {
                $selected = ($y == $year) ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>
        <button type="submit" style="background-color: #4CAF50; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Go</button>
        <button type="button" onclick="document.getElementById('yearSelectionForm').style.display='none';" style="background-color: #f44336; color: white; padding: 8px 16px; margin-left: 10px; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
    </form>
</div>
<?php endif; ?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    <?php if (!$displayForm) echo $database->generatePieChartJS($year); ?>
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

