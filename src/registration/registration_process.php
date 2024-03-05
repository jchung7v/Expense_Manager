<?php
session_start();

if (isset($_POST['register'])) {
    include("../components/utils.php");
    include("../connect_database.php");

    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $userEmail = sanitize_input($_POST['Email']);
    $userPassword = sanitize_input($_POST['Password']);
    $userRole = 'user';
    $userStatus = 0;


    // Hash the password
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
    
    // Check if email is valid
    $checkStmt = $db->prepare("SELECT COUNT(*) AS rowCount FROM users WHERE Email = ?");
    $checkStmt->bindParam(1, $userEmail, SQLITE3_TEXT);
    $result = $checkStmt->execute();
    $rowCount = $result->fetchArray(SQLITE3_NUM)[0];

    // If user ID already exists, return error message
    if ($rowCount > 0) {
        echo "<script>alert('User already exists'); window.location.href = './registration.php';</script>";
    } else {
        // Insert new user into database
        $insertStmt = $db->prepare("INSERT INTO users (Email, Password, Role, Status) VALUES (:Email, :Password, :Role, :Status)");
        $insertStmt->bindValue(':Email', $userEmail, SQLITE3_TEXT);
        $insertStmt->bindValue(':Password', $hashedPassword, SQLITE3_TEXT);
        $insertStmt->bindValue(':Role', $userRole, SQLITE3_TEXT);
        $insertStmt->bindValue(':Status', $userStatus, SQLITE3_NUM);
        $result = $insertStmt->execute();

        // If student was not created, return error message
        if ($result === false) {
            echo "<script>alert('Error creating new user.'); window.location.href = './registration.php';</script>";
        } else {
            echo "<script>alert('User created successfully'); window.location.href = '../index.php';</script>";
        }
    }

    $db->close();
}
?>