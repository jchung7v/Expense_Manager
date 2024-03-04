<?php 
session_start();

var_dump($_SESSION);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    $_SESSION['error_message'] = 'You must log in to view this page.';
    header('Location: ./index.php'); 
    exit();
}


include("inc_header.php"); 
?>

<h1>This is the Main Page</h1>

<?php include("inc_footer.php"); ?>