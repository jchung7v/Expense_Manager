<?php
if (isset($_POST['register'])) {
    include("../utils.php");
    include("../connect_database.php");

    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $userEmail = sanitize_input($_POST['Email']);
    $userPassword = sanitize_input($_POST['Password']);
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
        echo 'User ID already exists';
    } else {
        // Insert new user into database
        $insertStmt = $db->prepare("INSERT INTO users (Email, Password, Status) VALUES (:Email, :Password, :Status)");
        $insertStmt->bindValue(':Email', $userEmail, SQLITE3_TEXT);
        $insertStmt->bindValue(':Password', $userPassword, SQLITE3_TEXT);
        $insertStmt->bindValue(':Status', $userStatus, SQLITE3_NUM);
        $result = $insertStmt->execute();

        // If student was not created, return error message
        if ($result === false) {
            echo 'Error creating new user.';
        } else {
            echo 'User created successfully';
        }
    }

    $db->close();
}
?>