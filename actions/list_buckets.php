<?php 
session_start();
include("../inc_header.php");
include("../inc_header_main.php");
?>

<h1>Buckets List</h1>
<a href="../index.php">Back to Home</a>
<?php
if (isset($_SESSION['admin_signed_in']) && $_SESSION['admin_signed_in'] === true) {
    // Only show the "Add New Bucket" link if the user is an admin
    echo ' | <a href="add_bucket.php">Add New Bucket</a>';
}

include '../backend.php'; // Adjust the path as necessary

$db = new ExpenseDatabase();
$buckets = $db->getBuckets();

if (!empty($buckets)) {
    echo "<table border='1'><tr><th>Category</th><th>Vendor</th><th>Actions</th></tr>";
    foreach ($buckets as $bucket) {
        echo "<tr><td>{$bucket['category']}</td><td>{$bucket['vendor']}</td><td>";
        if (isset($_SESSION['admin_signed_in']) && $_SESSION['admin_signed_in'] === true) {
            // Only show the edit and delete links if the user is an admin
            echo "<a href='edit_bucket.php?vendor=".urlencode($bucket['vendor'])."'>Edit</a> | <a href='delete_bucket.php?vendor=".urlencode($bucket['vendor'])."'>Delete</a>";
        } else {
            echo "N/A"; // Placeholder text or you can leave it empty
        }
        echo "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No buckets found.";
}
?>

<?php include("../inc_footer.php"); ?>
