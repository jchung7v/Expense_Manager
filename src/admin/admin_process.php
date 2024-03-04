<?php
session_start();

if (isset($_POST['Admin-signin'])) {
    include("../utils.php");
    include("../connect_database.php");

    $adminEmail = sanitize_input($_POST['Email']);
    $adminPassword = sanitize_input($_POST['Password']);

    $checkStmt = $db->prepare("SELECT * FROM Admins WHERE Email = ?");
    $checkStmt->bindParam(1, $adminEmail, SQLITE3_TEXT);
    $result = $checkStmt->execute();

    if ($result === false) {
        $_SESSION['error_message'] = 'Error signing in';
        header('Location: admin_signin.php');
        exit();
    } else {
        $admin = $result->fetchArray(SQLITE3_ASSOC);
        if ($admin && password_verify($adminPassword, $admin['Password']) && $admin['Status'] == 1) {
            $_SESSION['admin_id'] = $admin['AdminId'];
            $_SESSION['admin_email'] = $admin['Email'];
            $_SESSION['admin_status'] = $admin['Status'];
            header('Location: users_list.php');
            
        } else {
            $_SESSION['error_message'] = 'Invalid email or password.';
            header('Location: admin_signin.php');
            exit();
        }
    }

    $db->close();
}
?>
