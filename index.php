<!DOCTYPE html>
<html>
<head>
    <title>Expense Manager</title>
</head>
<body>
    <h1>Expense Manager</h1>
    <div>
        <a href="list_transactions.php"><button>View Transactions</button></a>
        <a href="list_buckets.php"><button>View Buckets</button></a>
        <a href="report.php?year=2023"><button>Report</button></a>
    </div>
    <div>
        <form action="import_csv.php" method="post" enctype="multipart/form-data">
            <label for="fileToUpload">Select file to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <button type="submit" name="submit">Upload Selected File</button>
        </form>
    </div>
</body>
</html>
