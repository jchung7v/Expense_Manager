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

<h1>This is the Main Page</h1>

<?php include("inc_footer.php"); ?>