<!DOCTYPE html>
<html>
<head>
    <title>View Buckets</title>
</head>
<body>
    <h1>Buckets List</h1>
    <a href="index.php">Back to Home</a> | <a href="add_bucket.php">Add New Bucket</a>
    <?php
    include 'backend.php'; // Adjust the path as necessary

    $db = new ExpenseDatabase();
    $buckets = $db->getBuckets();

    if (!empty($buckets)) {
        echo "<table border='1'><tr><th>Category</th><th>Vendor</th><th>Actions</th></tr>";
        foreach ($buckets as $bucket) {
            // Edit link could pass both category and vendor, but here we just pass vendor for simplicity
            echo "<tr><td>{$bucket['category']}</td><td>{$bucket['vendor']}</td><td><a href='edit_bucket.php?vendor=".urlencode($bucket['vendor'])."'>Edit</a> | <a href='delete_bucket.php?vendor=".urlencode($bucket['vendor'])."'>Delete</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "No buckets found.";
    }
    ?>
</body>
</html>
