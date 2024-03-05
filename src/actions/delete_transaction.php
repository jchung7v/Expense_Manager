<?php
include '../backend.php';
$db = new ExpenseDatabase();

if (isset($_GET['id'])) {
    if ($db->deleteTransaction($_GET['id'])) {
        echo "<p>Transaction deleted successfully.</p>";
    } else {
        echo "<p>Error deleting transaction.</p>";
    }
} else {
    echo "<p>Transaction ID not specified.</p>";
}
header("Location: list_transactions.php"); // Redirect to the transactions list
?>
