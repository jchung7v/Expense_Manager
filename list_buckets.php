<!DOCTYPE html>
<html>
<head>
    <title>View Buckets</title>
</head>
<body>
    <h1>Buckets List</h1>
    <a href="index.php">Back to Home</a>
    <?php
    include 'backend.php'; // Adjust the path as necessary

    $db = new ExpenseDatabase();
    $buckets = $db->getBuckets();

    if (!empty($buckets)) {
        echo "<table border='1'><tr><th>Category</th><th>Vendor</th></tr>";
        foreach ($buckets as $bucket) {
            echo "<tr><td>{$bucket['category']}</td><td>{$bucket['vendor']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No buckets found.";
    }
    ?>
</body>
</html>
