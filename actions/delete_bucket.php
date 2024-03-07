<?php
include '../backend.php';
$db = new ExpenseDatabase();

if (isset($_GET['vendor'])) {
    $vendor = urldecode($_GET['vendor']);
    if ($db->deleteBucket($vendor)) {
        echo "Bucket deleted successfully.";
    } else {
        echo "Error deleting bucket.";
    }
} else {
    echo "Vendor not specified.";
}
// Redirect back to the buckets list
header('Location: list_buckets.php');
exit;
?>
