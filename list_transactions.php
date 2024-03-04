<!DOCTYPE html>
<html>
<head>
    <title>View Transactions</title>
</head>
<body>
    <h1>Transactions List</h1>
    <a href="index.php">Back to Home</a>
    <?php
    include 'backend.php'; // Adjust the path as necessary

    $db = new ExpenseDatabase();
    $transactions = $db->getTransactions();

    if (!empty($transactions)) {
        echo "<table border='1'><tr><th>Date</th><th>Vendor</th><th>Withdraw</th><th>Deposit</th><th>Balance</th></tr>";
        foreach ($transactions as $transaction) {
            echo "<tr><td>{$transaction['date']}</td><td>{$transaction['vendor']}</td><td>{$transaction['withdraw']}</td><td>{$transaction['deposit']}</td><td>{$transaction['balance']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No transactions found.";
    }
    ?>
</body>
</html>
