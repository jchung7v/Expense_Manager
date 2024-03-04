<?php

// Path to the CSV file
$csvFilePath = '2023 02.csv';

// Connect to SQLite database
$db = new SQLite3('expenses.db');

// Create the 'transactions' table
$db->exec("CREATE TABLE IF NOT EXISTS transactions (
    date TEXT,
    vendor TEXT,
    withdraw REAL,
    deposit REAL,
    balance REAL
)");

// Create the 'buckets' table
$db->exec("CREATE TABLE IF NOT EXISTS buckets (
    category TEXT,
    vendor TEXT UNIQUE
)");

// Function to determine the category based on vendor name
function getCategory($vendor) {
    if (strpos($vendor, "RESTAURAT") !== false) return "Entertainment";
    if (strpos($vendor, "MOBILE") !== false) return "Communication";
    if (strpos($vendor, "SAFEWAY") !== false || strpos($vendor, "SUPERS") !== false) return "Groceries";
    if (strpos($vendor, "RED CROSS") !== false) return "Donations";
    if (strpos($vendor, "ICBC") !== false) return "Car Insurance";
    if (strpos($vendor, "FORTISBC") !== false) return "Gas Heating";
    // Add more categories as needed
    return "Other"; // Default category if none of the above matches
}

// Open and read the CSV file
if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
    fgetcsv($handle); // Skip the header row

    while (($data = fgetcsv($handle)) !== FALSE) {
        // Prepare the insert statement for 'transactions' table
        $stmt = $db->prepare("INSERT INTO transactions (date, vendor, withdraw, deposit, balance) VALUES (?, ?, ?, ?, ?)");
        
        // Bind values to the prepared statement
        $stmt->bindValue(1, $data[0], SQLITE3_TEXT);
        $stmt->bindValue(2, trim($data[1]), SQLITE3_TEXT);
        $stmt->bindValue(3, empty($data[2]) ? NULL : $data[2], SQLITE3_FLOAT);
        $stmt->bindValue(4, empty($data[3]) ? NULL : $data[3], SQLITE3_FLOAT);
        $stmt->bindValue(5, $data[4], SQLITE3_FLOAT);

        // Execute the statement
        $stmt->execute();

        // Determine the category for the vendor
        $category = getCategory(trim($data[1]));

        // Prepare the insert statement for 'buckets' table
        $stmt = $db->prepare("INSERT OR IGNORE INTO buckets (category, vendor) VALUES (?, ?)");
        
        // Bind values to the prepared statement
        $stmt->bindValue(1, $category, SQLITE3_TEXT);
        $stmt->bindValue(2, trim($data[1]), SQLITE3_TEXT);

        // Execute the statement
        $stmt->execute();
    }
    fclose($handle);
}

echo "CSV data imported into 'transactions' and 'buckets' tables successfully.";

?>
