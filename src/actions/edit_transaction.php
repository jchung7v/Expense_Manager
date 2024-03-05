<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaction</title>
</head>
<body>
    <h1>Edit Transaction</h1>
    <?php
    include '../backend.php';
    $db = new ExpenseDatabase();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Process the form submission
        if ($db->updateTransaction($_POST['id'], $_POST['date'], $_POST['vendor'], $_POST['withdraw'], $_POST['deposit'], $_POST['balance'])) {
            echo "<p>Transaction updated successfully.</p>";
            header("Location: list_transactions.php"); // Redirect to avoid resubmission
        } else {
            echo "<p>Error updating transaction.</p>";
        }
    } else {
        // Display the form with pre-filled transaction data
        $transaction = null;
        if (isset($_GET['id'])) {
            $transaction = $db->getTransactionById($_GET['id']);
        }

        if (!$transaction) {
            echo "<p>Transaction not found.</p>";
        } else {
            ?>
            <form action="edit_transaction.php" method="post">
                <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $transaction['date']; ?>" required><br>
                <label for="vendor">Vendor:</label>
                <input type="text" id="vendor" name="vendor" value="<?php echo $transaction['vendor']; ?>" required><br>
                <label for="withdraw">Withdraw:</label>
                <input type="number" step="0.01" id="withdraw" name="withdraw" value="<?php echo $transaction['withdraw']; ?>" required><br>
                <label for="deposit">Deposit:</label>
                <input type="number" step="0.01" id="deposit" name="deposit" value="<?php echo $transaction['deposit']; ?>" required><br>
                <label for="balance">Balance:</label>
                <input type="number" step="0.01" id="balance" name="balance" value="<?php echo $transaction['balance']; ?>" required><br>
                <input type="submit" value="Update Transaction">
            </form>
            <?php
        }
    }
    ?>
    <a href="list_transactions.php">Back to Transactions List</a>
</body>
</html>
