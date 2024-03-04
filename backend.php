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
        // Initialize an array to hold category totals, including "Other"
        $categoryTotals = [
            'Entertainment' => 0,
            'Communication' => 0,
            'Groceries' => 0,
            'Donations' => 0,
            'Car Insurance' => 0,
            'Gas Heating' => 0,
            'Other' => 0 // Initialize "Other" category for unmatched transactions
        ];
    
        $stmt = $this->db->prepare("SELECT t.date, t.vendor, t.withdraw
                                    FROM transactions t
                                    WHERE strftime('%Y', t.date) = ?");
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
    

}

?>
