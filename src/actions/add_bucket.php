<?php 
session_start();
include("../inc_header.php");
include("../inc_header_main.php");

if (isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo "<script>alert('You must log in as an admin to view this page.'); window.location.href = '/actions/list_buckets.php';</script>";
    exit();
}
?>
    <h3>Add New Bucket</h3>
    <a href="list_buckets.php">Back to Buckets List</a>
    <br>
    <form action="add_bucket.php" method="post">
        <div class="form-group mb-3">
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required><br>
        </div>
        <div class="form-group mb-3">
            <label for="vendor">Vendor:</label>
            <input type="text" id="vendor" name="vendor" required><br>
        </div>
        <input type="submit" value="Add Bucket" class="btn btn-primary mt-3">
    </form>
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
<?php include("../inc_footer.php"); ?>

