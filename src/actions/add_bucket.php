<!DOCTYPE html>
<html>
<head>
    <title>Add Bucket</title>
</head>
<body>
    <h1>Add New Bucket</h1>
    <form action="add_bucket.php" method="post">
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required><br>
        <label for="vendor">Vendor:</label>
        <input type="text" id="vendor" name="vendor" required><br>
        <input type="submit" value="Add Bucket">
    </form>
    <a href="list_buckets.php">Back to Buckets List</a>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include '../backend.php';
        $db = new ExpenseDatabase();
        if ($db->addBucket($_POST['category'], $_POST['vendor'])) {
            echo "Bucket added successfully.";
        } else {
            echo "Error adding bucket.";
        }
    }
    ?>
</body>
</html>
