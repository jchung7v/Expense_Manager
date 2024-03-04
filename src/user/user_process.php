<?php
session_start();

if (isset($_POST['User-signin'])) {
    include("../utils.php");
    include("../connect_database.php");

    $userEmail = sanitize_input($_POST['Email']);
    $userPassword = sanitize_input($_POST['Password']);

    $checkStmt = $db->prepare("SELECT * FROM Users WHERE Email = ?");
    $checkStmt->bindParam(1, $userEmail, SQLITE3_TEXT);
    $result = $checkStmt->execute();

    if ($result === false) {
        $_SESSION['error_message'] = 'Error signing in';
        header('Location: ../index.php');
        exit();
    } else {
        $user = $result->fetchArray(SQLITE3_ASSOC);
        if ($user && password_verify($userPassword, $user['Password'])) {
            if ($user['Status'] == 1) {
                $_SESSION['user_id'] = $user['UserId'];
                $_SESSION['user_email'] = $user['Email'];
                $_SESSION['user_status'] = $user['Status'];
                header('Location: ../main.php');
                exit();
            }
            else {
                $_SESSION['error_message'] = 'User is not authorized.';
                header('Location: ../index.php');
                exit();
            }
        } else {
            $_SESSION['error_message'] = 'Invalid email or password.';
            header('Location: ../index.php');
            exit();
        }
    }

    $db->close();
}
?>