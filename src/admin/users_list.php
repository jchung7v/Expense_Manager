<?php 
include("../inc_header.php");

session_start();

if (isset($_SESSION["admin_signed_in"])) {
    echo "You are signed in as " . htmlspecialchars($_SESSION['admin_role']);
}
?>

<h1>Admin Page</h1>

<?php

include("../connect_database.php");

// If the user is not signed in as an admin, redirect to the sign-in page
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    echo "<script>alert('You must log in as an admin to view this page.'); window.location.href = './admin_singin.php';</script>";
    exit();
}

echo "<a href='../components/signout.php'>Sign-out</a>";
echo "<a href='../main.php'>Main Page</a>";

$resultSet = $db->query('SELECT * FROM Users');

$col0 = $resultSet->columnName(0);
$col1 = $resultSet->columnName(1);
$col2 = $resultSet->columnName(2);


echo "<table width='100%' class='table table-striped'>\n";
echo "<tr><th>ID</th>".
     "<th>Email</th>".
     "<th>Hashed-Password</th>".
     "<th>Authorization</th></tr>\n";

while ($row = $resultSet->fetchArray()) {
    $toggleStatus = $row[4] == 1 ? 0 : 1; // Status to toggle
    $toggleText = $row[4] == 1 ? 'Deauthorize' : 'Authorize';  // Text to display on the button
    $buttonClass = $row[4] == 1 ? 'btn-danger' : 'btn-success';  // Button class

    echo "<tr>";
    echo "<td>{$row[0]}</td>";
    echo "<td>{$row[1]}</td>";
    echo "<td>{$row[2]}</td>";
    echo "<td>";
    echo "<a class='btn btn-small $buttonClass' href='./update_user_status.php?id={$row[0]}&status=$toggleStatus'>$toggleText</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>\n";

$db->close();
?>

<?php include("../inc_footer.php"); ?>
