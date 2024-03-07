<?php 
session_start();
include("../inc_header.php");
include("../inc_header_main.php");
?>
    <h3>Transactions List</h3>
    <a href="add_transaction.php">Add New Transaction</a>
    <?php
    include '../backend.php';

    $db = new ExpenseDatabase();
    $transactions = $db->getTransactions();

    if (!empty($transactions)) {
        echo "<table border='1'><tr><th>Date</th><th>Vendor</th><th>Withdraw</th><th>Deposit</th><th>Balance</th><th>Actions</th></tr>";
        foreach ($transactions as $transaction) {
            echo "<tr>
                    <td>{$transaction['date']}</td>
                    <td>{$transaction['vendor']}</td>
                    <td>{$transaction['withdraw']}</td>
                    <td>{$transaction['deposit']}</td>
                    <td>{$transaction['balance']}</td>
                    <td><a href='edit_transaction.php?id={$transaction['id']}'>Edit</a> | <a href='delete_transaction.php?id={$transaction['id']}' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No transactions found.";
    }
    ?>

<?php include("../inc_footer.php"); ?>
