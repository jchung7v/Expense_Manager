<?php 
session_start();
include("../inc_header.php");
include("../inc_header_main.php");
?>
    <h3>Add New Transaction</h3>
    <a href="list_transactions.php">Back to Transactions List</a>
    <br>
    <form action="add_transaction.php" method="post">
        <div class="form-group mb-3">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br>
        </div>
        <div class="form-group mb-3">
            <label for="vendor">Vendor:</label>
            <input type="text" id="vendor" name="vendor" required><br>
        </div>
        <div class="form-group mb-3">
            <label for="withdraw">Withdraw:</label>
            <input type="number" step="0.01" id="withdraw" name="withdraw"><br>
        </div>
        <div class="form-group mb-3">
            <label for="deposit">Deposit:</label>
            <input type="number" step="0.01" id="deposit" name="deposit"><br>
        </div>
        <div class="form-group mb-3">
            <label for="balance">Balance:</label>
            <input type="number" step="0.01" id="balance" name="balance" required><br>
        </div>
        <input type="submit" value="Add Transaction" class="btn btn-primary mt-3">
    </form>
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
<?php include("../inc_footer.php"); ?>

