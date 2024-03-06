<?php
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo "<script>alert('You must log in to view this page.'); window.location.href = '/index.php';</script>";
    exit();
}

echo "<h1>Expense Manager</h1>";

if (isset($_SESSION["admin_signed_in"])) {
    echo "You are signed in as an " . htmlspecialchars($_SESSION['admin_role']);
} elseif (isset($_SESSION["user_signed_in"])) {
    echo "You are signed in as a " . htmlspecialchars($_SESSION['user_role']);
}
?>

<nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom mb-5" data-bs-theme="dark">
  <div class="container">
    <ul class="navbar-nav flex-grow-1 justify-content-between">
        <li class="nav-item"><a class="nav-link" href="/actions/list_transactions.php">View Transactions</a></li>
        <li class="nav-item"><a class="nav-link" href="/actions/list_buckets.php">View Buckets</a></li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="document.getElementById('yearSelectionForm').style.display='block'; return false;">Report</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="../components/signout.php">Sign out</a></li>
    </ul>
  </div>
</nav>

<!-- Enhanced form with dark mode styling -->
<div id="yearSelectionForm" style="display:none; position:fixed; left:50%; top:50%; transform:translate(-50%, -50%); background-color:#333; color: #fff; padding:20px; border-radius:10px; z-index: 1000; box-shadow: 0 0 15px rgba(0,0,0,0.5);">
    <form action="/actions/report.php" method="GET">
        <label for="year" style="color: #fff;">Select a year:</label>
        <select name="year" id="year" style="color: #333; background-color: #fff; padding: 4px; border-radius: 5px; margin-right: 10px;">
            <?php
            $currentYear = date('Y');
            for ($year = $currentYear; $year >= 1900; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select>
        <button type="submit" style="background-color: #4CAF50; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">Go</button>
        <button type="button" onclick="document.getElementById('yearSelectionForm').style.display='none';" style="background-color: #f44336; color: white; padding: 8px 16px; margin-left: 10px; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
    </form>
</div>

<script>
// Optional: Automatically show the form when the page loads, if desired
// window.onload = function() { document.getElementById('yearSelectionForm').style.display='block'; };
</script>
