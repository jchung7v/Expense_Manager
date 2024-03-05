<!DOCTYPE html>
<html>
<head>
    <title>Add Transaction</title>
</head>
<body>
    <h1>Add New Transaction</h1>
    <form action="add_transaction.php" method="post">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br>
        <label for="vendor">Vendor:</label>
        <input type="text" id="vendor" name="vendor" required><br>
        <label for="withdraw">Withdraw:</label>
        <input type="number" step="0.01" id="withdraw" name="withdraw"><br>
        <label for="deposit">Deposit:</label>
        <input type="number" step="0.01" id="deposit" name="deposit"><br>
        <label for="balance">Balance:</label>
        <input type="number" step="0.01" id="balance" name="balance" required><br>
        <input type="submit" value="Add Transaction">
    </form>
    <a href="list_transactions.php">Back to Transactions List</a>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include '../backend.php';
        $db = new ExpenseDatabase();
        // Extract and sanitize input
        $date = $_POST['date'];
        $vendor = $_POST['vendor'];
        $withdraw = empty($_POST['withdraw']) ? 0 : $_POST['withdraw'];
        $deposit = empty($_POST['deposit']) ? 0 : $_POST['deposit'];
        $balance = $_POST['balance'];

        if ($db->addTransaction($date, $vendor, $withdraw, $deposit, $balance)) {
            echo "<p>Transaction added successfully.</p>";
        } else {
            echo "<p>Error adding transaction.</p>";
        }
    }
    ?>
</body>
</html>
