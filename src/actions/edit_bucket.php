<!DOCTYPE html>
<html>
<head>
    <title>Edit Bucket</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        form {
            background-color: #333;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        label {
            color: #fff;
        }
        input[type=text], input[type=submit], select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #333;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        a {
            color: #9A9CFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

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
