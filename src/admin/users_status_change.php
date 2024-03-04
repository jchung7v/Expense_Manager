<?php
include("../connect_database.php");

session_start();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $userId = $_GET['id'];
    $newStatus = $_GET['status'];

    $updateStmt = $db->prepare('UPDATE Users SET Status = :Status WHERE UserId = :UserId');
    $updateStmt->bindValue(':Status', $newStatus, SQLITE3_INTEGER);
    $updateStmt->bindValue(':UserId', $userId, SQLITE3_INTEGER);
    $result = $updateStmt->execute();

    if ($result) {
        echo "User status updated successfully.";
    } else {
        echo "Failed to update user status.";
    }
}

$db->close();

header('Location: users_list.php');
exit();
?>
