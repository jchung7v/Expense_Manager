<?php 
session_start();
include("./inc_header.php");

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo "<script>alert('You must log in to view this page.'); window.location.href = './index.php';</script>";
    exit();
}

if (isset($_SESSION["admin_signed_in"])) {
    echo "You are signed in as " . htmlspecialchars($_SESSION['admin_role']);
} elseif (isset($_SESSION["user_signed_in"])) {
    echo "You are signed in as " . htmlspecialchars($_SESSION['user_role']);
}

echo "<a href='./components/signout.php'>Sign-out</a>";
?>

<h1>Expense Manager</h1>

<div>
    <a href="./actions/list_transactions.php"><button>View Transactions</button></a>
    <a href="./actions/list_buckets.php"><button>View Buckets</button></a>
    <a href="./actions/report.php?year=2023"><button>Report</button></a>
</div>
<div>
    <form action="./actions/import_csv.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select file to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <button type="submit" name="submit">Upload Selected File</button>
    </form>
</div>

<?php include("inc_footer.php"); ?>