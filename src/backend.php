<?php

class ExpenseDatabase {
    private $db;

    public function __construct($dbName = 'expenses.db') {
        $this->db = new SQLite3($dbName);
        $this->initializeTables();
        $this->initializePredefinedBuckets();
    }

    private function initializeTables() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            date TEXT,
            vendor TEXT,
            withdraw REAL,
            deposit REAL,
            balance REAL
        )");
    
        $this->db->exec("CREATE TABLE IF NOT EXISTS buckets (
            category TEXT,
            vendor TEXT,
            PRIMARY KEY (category, vendor)
        )");
    }    

    private function initializePredefinedBuckets() {
        $predefinedBuckets = [
            ['Entertainment', 'ST JAMES RESTAURAT'],
            ['Entertainment', 'PUR & SIMPLE RESTAUR'],
            ['Entertainment', 'Subway'],
            ['Entertainment', 'WHITE SPOT RESTAURAN'],
            ['Entertainment', 'MCDONALDS'],
            ['Entertainment', 'TIM HORTONS'],
            ['Groceries', 'SAFEWAY'],
            ['Groceries', 'SAFEWAY #4913'],
            ['Groceries', 'REAL CDN SUPERS'],
            ['Groceries', 'WALMART STORE'],
            ['Groceries', 'COSTCO WHOLESAL'],
            ['Groceries', '7-ELEVEN STORE'],
            ['Communication', 'ROGERS MOBILE'],
            ['Car Insurance', 'ICBC'],
            ['Gas Heating', 'FORTISBC'],
            ['Donations', 'RED CROSS'],
            ['Banking', 'GATEWAY'],
            ['Banking', 'CHQ'],
            ['Banking', 'FEE'],
        ];

        foreach ($predefinedBuckets as $bucket) {
            $stmt = $this->db->prepare("INSERT OR IGNORE INTO buckets (category, vendor) VALUES (?, ?)");
            $stmt->bindValue(1, $bucket[0], SQLITE3_TEXT);
            $stmt->bindValue(2, $bucket[1], SQLITE3_TEXT);
            $stmt->execute();
        }
    }

    public function importCSV($csvFilePath) {
        if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
            fgetcsv($handle); // Skip the header row

            while (($data = fgetcsv($handle)) !== FALSE) {
                $this->insertTransaction($data);
                $this->insertBucket(trim($data[1])); // This might need adjustment based on new logic.
            }
            fclose($handle);
            echo "CSV data imported into 'transactions' and 'buckets' tables successfully.";
        }
    }

    private function insertTransaction($data) {
        // Convert date from MM/DD/YYYY to YYYY-MM-DD
        $date = DateTime::createFromFormat('m/d/Y', $data[0]);
        $formattedDate = $date->format('Y-m-d');
    
        $stmt = $this->db->prepare("INSERT INTO transactions (date, vendor, withdraw, deposit, balance) VALUES (?, ?, ?, ?, ?)");
    
        $stmt->bindValue(1, $formattedDate, SQLITE3_TEXT);
        $stmt->bindValue(2, $data[1], SQLITE3_TEXT);
        $stmt->bindValue(3, empty($data[2]) ? NULL : $data[2], SQLITE3_FLOAT);
        $stmt->bindValue(4, empty($data[3]) ? NULL : $data[3], SQLITE3_FLOAT);
        $stmt->bindValue(5, $data[4], SQLITE3_FLOAT);
    
        $stmt->execute();
    }    

    private function insertBucket($vendor) {
        // This method may be simplified or adjusted since categories are now predefined.
    }

    public function getTransactions() {
        $result = $this->db->query('SELECT * FROM transactions');
        $transactions = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $transactions[] = $row;
        }
        return $transactions;
    }

    public function getBuckets() {
        $result = $this->db->query('SELECT * FROM buckets');
        $buckets = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $buckets[] = $row;
        }
        return $buckets;
    }

    public function generateReport($year) {
        // Fetch unique categories from the buckets table
        $categoriesResult = $this->db->query("SELECT DISTINCT category FROM buckets");
        $categoryTotals = [];
        while ($category = $categoriesResult->fetchArray(SQLITE3_ASSOC)) {
            // Initialize each category total to 0
            $categoryTotals[$category['category']] = 0;
        }
        // Ensure "Other" category is also initialized
        $categoryTotals['Other'] = 0;
    
        $stmt = $this->db->prepare("SELECT t.date, t.vendor, t.withdraw FROM transactions t WHERE strftime('%Y', t.date) = ?");
        $stmt->bindValue(1, $year, SQLITE3_TEXT);
        $transactions = $stmt->execute();
    
        while ($transaction = $transactions->fetchArray(SQLITE3_ASSOC)) {
            $matched = false;
            // Attempt to match transaction vendor with a predefined bucket
            foreach ($this->getBuckets() as $bucket) {
                if (strpos($transaction['vendor'], trim($bucket['vendor'])) !== false) {
                    $categoryTotals[$bucket['category']] += $transaction['withdraw'];
                    $matched = true;
                    break;
                }
            }
            // If no predefined bucket matches, categorize as "Other"
            if (!$matched) {
                $categoryTotals['Other'] += $transaction['withdraw'];
            }
        }
    
        return $categoryTotals;
    }    

    public function generateReportTable($year) {
        $report = $this->generateReport($year);
        $html = "<h2>Expense Report for Year: $year</h2>";
        $html .= "<table border='1'><tr><th>Category</th><th>Amount</th></tr>";
        $total = 0;
        foreach ($report as $category => $amount) {
            $html .= "<tr><td>" . htmlspecialchars($category) . "</td><td>" . number_format($amount, 2) . "</td></tr>";
            $total += $amount;
        }
        $html .= "<tr><td><strong>Total</strong></td><td><strong>" . number_format($total, 2) . "</strong></td></tr>";
        $html .= "</table>";
        return $html;
    }

    public function generatePieChartJS($year) {
        $report = $this->generateReport($year);
        $js = "google.charts.load('current', {'packages':['corechart']});";
        $js .= "google.charts.setOnLoadCallback(drawChart);";
        $js .= "function drawChart() {";
        $js .= "var data = google.visualization.arrayToDataTable([";
        $js .= "['Category', 'Amount'],";
        foreach ($report as $category => $amount) {
            $js .= "['" . addslashes($category) . "', " . $amount . "],";
        }
        $js .= "]);";
        $js .= "var options = {is3D: true,};";
        $js .= "var chart = new google.visualization.PieChart(document.getElementById('piechart'));";
        $js .= "chart.draw(data, options);";
        $js .= "}";
        return $js;
    }

    // Add a Bucket
    public function addBucket($category, $vendor) {
        $stmt = $this->db->prepare("INSERT INTO buckets (category, vendor) VALUES (?, ?)");
        $stmt->bindValue(1, $category, SQLITE3_TEXT);
        $stmt->bindValue(2, $vendor, SQLITE3_TEXT);
        return $stmt->execute();
    }

    public function getBucketByVendor($vendor) {
        $stmt = $this->db->prepare("SELECT * FROM buckets WHERE vendor = ?");
        $stmt->bindValue(1, $vendor, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC); // Fetches a single bucket
    }
    
    // Edit a Bucket
    // Assumes you have a unique identifier for buckets. If not, you'll need additional logic.
    public function editBucket($category, $vendor, $oldVendor) {
        $stmt = $this->db->prepare("UPDATE buckets SET category = ?, vendor = ? WHERE vendor = ?");
        $stmt->bindValue(1, $category, SQLITE3_TEXT);
        $stmt->bindValue(2, $vendor, SQLITE3_TEXT);
        $stmt->bindValue(3, $oldVendor, SQLITE3_TEXT); // Assuming vendor is unique enough for this example
        return $stmt->execute();
    }

    // Delete a Bucket
    public function deleteBucket($vendor) {
        $stmt = $this->db->prepare("DELETE FROM buckets WHERE vendor = ?");
        $stmt->bindValue(1, $vendor, SQLITE3_TEXT);
        return $stmt->execute();
    }

    // Add a Transaction
    public function addTransaction($date, $vendor, $withdraw, $deposit, $balance) {
        $stmt = $this->db->prepare("INSERT INTO transactions (date, vendor, withdraw, deposit, balance) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $date, SQLITE3_TEXT);
        $stmt->bindValue(2, $vendor, SQLITE3_TEXT);
        $stmt->bindValue(3, $withdraw, SQLITE3_FLOAT);
        $stmt->bindValue(4, $deposit, SQLITE3_FLOAT);
        $stmt->bindValue(5, $balance, SQLITE3_FLOAT);
        return $stmt->execute();
    }

    public function getTransactionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC); // Fetches a single transaction
    }
     
    // Update a Transaction (assuming you have a unique identifier for each transaction, like an ID)
    public function updateTransaction($id, $date, $vendor, $withdraw, $deposit, $balance) {
        $stmt = $this->db->prepare("UPDATE transactions SET date = ?, vendor = ?, withdraw = ?, deposit = ?, balance = ? WHERE id = ?");
        $stmt->bindValue(1, $date, SQLITE3_TEXT);
        $stmt->bindValue(2, $vendor, SQLITE3_TEXT);
        $stmt->bindValue(3, $withdraw, SQLITE3_FLOAT);
        $stmt->bindValue(4, $deposit, SQLITE3_FLOAT);
        $stmt->bindValue(5, $balance, SQLITE3_FLOAT);
        $stmt->bindValue(6, $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete a Transaction
    public function deleteTransaction($id) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }
    
}

?>
