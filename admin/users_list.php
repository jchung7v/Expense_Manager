<?php 
session_start();
include("../inc_header.php");
include("../connect_database.php");
?>

<h1>Admin Page</h1>

<nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom mb-5" data-bs-theme="dark">
  <div class="container">
    <ul class="navbar-nav flex-grow-1 justify-content-between">
        <li class="nav-item"><a class="nav-link" href="#">
        </a></li>
        <li class="nav-item"><a class="nav-link" href="./users_list.php">User List</a></li>
        <li class="nav-item"><a class="nav-link" href="../main.php">Main Page</a></li>
        <li class="nav-item"><a class="nav-link" href="../components/signout.php">Sign out</a></li>
        <li class="nav-item"><a class="nav-link" href="#">
        </a></li>
    </ul>
  </div>
</nav>

<?php

// If the user is not signed in as an admin, redirect to the sign-in page
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    echo "<script>alert('You must log in as an admin to view this page.'); window.location.href = './admin_singin.php';</script>";
    exit();
}

$resultSet = $db->query('SELECT * FROM Users');

$col0 = $resultSet->columnName(0);
$col1 = $resultSet->columnName(1);

echo "<table width='100%' class='table table-striped'>\n";
echo "<tr><th>ID</th>".
     "<th>Email</th>".
     "<th>Authorization</th></tr>\n";

while ($row = $resultSet->fetchArray()) {
    $toggleStatus = $row[4] == 1 ? 0 : 1; // Status to toggle
    $toggleText = $row[4] == 1 ? 'Deauthorize' : 'Authorize';  // Text to display on the button
    $buttonClass = $row[4] == 1 ? 'btn-danger' : 'btn-success';  // Button class

    echo "<tr>";
    echo "<td>{$row[0]}</td>";
    echo "<td>{$row[1]}</td>";
    echo "<td>";
    echo "<a class='btn btn-small $buttonClass' href='./update_user_status.php?id={$row[0]}&status=$toggleStatus'>$toggleText</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>\n";

$db->close();
?>

<?php include("../inc_footer.php"); ?>
