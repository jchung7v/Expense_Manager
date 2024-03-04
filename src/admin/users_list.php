<?php include("../inc_header.php"); ?>

<?php
session_start();
include("../connect_database.php");

$resultSet = $db->query('SELECT * FROM Users');

$col0 = $resultSet->columnName(0);
$col1 = $resultSet->columnName(1);
$col2 = $resultSet->columnName(2);

echo "<table width='100%' class='table table-striped'>\n";
echo "<tr><th>$col0</th>".
     "<th>$col1</th>".
     "<th>$col2</th>".
     "<th>Authorization</th></tr>\n";

while ($row = $resultSet->fetchArray()) {
    $toggleStatus = $row[3] == 1 ? 0 : 1; // Status to toggle
    $toggleText = $row[3] == 1 ? 'Deauthorize' : 'Authorize';  // Text to display on the button
    $buttonClass = $row[3] == 1 ? 'btn-danger' : 'btn-success';  // Button class

    echo "<tr>";
    echo "<td>{$row[0]}</td>";
    echo "<td>{$row[1]}</td>";
    echo "<td>{$row[2]}</td>";
    echo "<td>";
    echo "<a class='btn btn-small $buttonClass' href='./users_status_change.php?id={$row[0]}&status=$toggleStatus'>$toggleText</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>\n";

$db->close();
?>

<?php include("../inc_footer.php"); ?>
