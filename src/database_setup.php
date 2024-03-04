<?php

include("connect_database.php");

$version = $db->querySingle('SELECT SQLITE_VERSION()');

// Create User table if it does not exist
$SQL_create_user_table = "CREATE TABLE IF NOT EXISTS Users (
    UserId INTEGER PRIMARY KEY,
    Email VARCHAR(80),
    Password VARCHAR(200),
    Status INTEGER
);";

$db->exec($SQL_create_user_table);

// Create Admin table if it does not exist
$SQL_create_admin_table = "CREATE TABLE IF NOT EXISTS Admins (
    AdminId INTEGER PRIMARY KEY,
    Email VARCHAR(80),
    Password VARCHAR(200),
    Status INTEGER
);";

$db->exec($SQL_create_admin_table);

// Check if Admins table is empty
$tableName = 'Admins';
$query_rows = $db ->query("SELECT COUNT(*) AS 'rowCount' FROM $tableName");
$result = $query_rows->fetchArray(SQLITE3_ASSOC);
$rowCount = $result['rowCount'];

// If Admins table is empty, insert default admin(aa@aa.aa, P@$$wOrd)
if ($rowCount == 0) {
    $hashedPassword = password_hash('P@$$wOrd', PASSWORD_DEFAULT);
    $SQL_insert_data = "INSERT INTO Admins (Email, Password, Status) VALUES (?, ?, ?)";

    $stmt = $db->prepare($SQL_insert_data);
    $stmt->bindParam(1, $adminEmail);
    $stmt->bindParam(2, $hashedPassword);
    $stmt->bindParam(3, $adminStatus);

    // Set parameters and execute
    $adminEmail = 'aa@aa.aa';
    $adminStatus = 1;
    $stmt->execute();
}

// Check if Users table is empty
$tableName = 'Users';
$query_rows = $db ->query("SELECT COUNT(*) AS 'rowCount' FROM $tableName");
$result = $query_rows->fetchArray(SQLITE3_ASSOC);
$rowCount = $result['rowCount'];

// If Users table is empty, insert dummy users
if ($rowCount == 0) {
    $SQL_insert_data = "INSERT INTO Users (Email, Password, Status) VALUES (?, ?, ?)";

    $stmt = $db->prepare($SQL_insert_data);
    $stmt->bindParam(1, $userEmail);
    $stmt->bindParam(2, $hashedPassword);
    $stmt->bindParam(3, $userStatus);

    // Set parameters and execute
    $userEmail = 'abc@abc.abc';
    $userStatus = 0;
    $hashedPassword = password_hash('123', PASSWORD_DEFAULT);
    $stmt->execute();
}


$db->close();
?>
