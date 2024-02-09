<?php
// Connect to the SQLite database
$database = new SQLite3('mydatabase.db');

// Create the table with the necessary columns
$createTableQuery = <<<SQL
CREATE TABLE IF NOT EXISTS transactions (
    date TEXT,
    vendor TEXT,
    withdraw FLOAT,
    balance FLOAT
);
SQL;

$database->exec($createTableQuery);

// Open the CSV file
$file = fopen('./2023 02.csv', 'r');

// Skip the header row if your CSV has headers
$headers = fgetcsv($file);

// Read and insert the data into the SQLite database
while ($row = fgetcsv($file)) {
    // Prepare the INSERT statement with placeholders
    $stmt = $database->prepare('INSERT INTO transactions (date, vendor, withdraw, balance) VALUES (:date, :vendor, :withdraw, :balance)');

    // Bind the CSV values to the placeholders
    $stmt->bindValue(':date', $row[0]);
    $stmt->bindValue(':vendor', $row[1]);
    $stmt->bindValue(':withdraw', $row[2]);
    $stmt->bindValue(':balance', $row[3]);

    // Execute the prepared statement
    $stmt->execute();
}

// Close the CSV file
fclose($file);

// Close the database connection
$database->close();

echo "Data import complete.";
?>
