<?php
session_start();
include("../connect_database.php");

// Only admin can change user status
if (isset($_GET['id'], $_GET['status'], $_SESSION['admin_id'])) {
    $userId = $_GET['id'];
    $newStatus = $_GET['status'];

    $updateStmt = $db->prepare('UPDATE Users SET Status = :Status WHERE UserId = :UserId');
    $updateStmt->bindValue(':Status', $newStatus, SQLITE3_INTEGER);
    $updateStmt->bindValue(':UserId', $userId, SQLITE3_INTEGER);
    $result = $updateStmt->execute();

    if ($result) {
        echo "<script>alert('User status updated successfully.');</script>";
    } else {
        echo "<script>alert('Failed to update user status.');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
}

$db->close();
header('Location: users_list.php');
exit();
?>