<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaction</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        form {
            background-color: #333;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        label {
            color: #fff;
        }
        input[type=date], input[type=text], input[type=number], input[type=submit] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #333;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        a {
            color: #9A9CFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
include '../backend.php';
$db = new ExpenseDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($db->updateTransaction($_POST['id'], $_POST['date'], $_POST['vendor'], $_POST['withdraw'], $_POST['deposit'], $_POST['balance'])) {
        echo "<p>Transaction updated successfully.</p>";
        header("Location: list_transactions.php"); // Redirect to avoid resubmission
        exit;
    } else {
        echo "<p>Error updating transaction.</p>";
    }
} else {
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
