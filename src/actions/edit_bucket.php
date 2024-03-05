<?php
include '../backend.php'; // Adjust the path as necessary
$db = new ExpenseDatabase();

$vendor = isset($_GET['vendor']) ? urldecode($_GET['vendor']) : '';

// Fetch existing bucket details
$bucket = null;
if ($vendor) {
    $bucket = $db->getBucketByVendor($vendor);
}

// Handle form submission to update the bucket
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newVendor = $_POST['vendor'];
    $category = $_POST['category'];

    if ($db->editBucket($category, $newVendor, $vendor)) {
        header('Location: list_buckets.php'); // Redirect to the buckets list
        exit;
    } else {
        echo "Error updating bucket.";
    }
}

if (!$bucket) {
    echo "Bucket not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Bucket</title>
</head>
<body>
    <h1>Edit Bucket</h1>
    <form action="" method="post">
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($bucket['category']); ?>" required><br>
        <label for="vendor">Vendor:</label>
        <input type="text" id="vendor" name="vendor" value="<?php echo htmlspecialchars($bucket['vendor']); ?>" required><br>
        <input type="submit" value="Update Bucket">
    </form>
    <a href="list_buckets.php">Back to Buckets List</a>
</body>
</html>
