<?php
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo "<script>alert('You must log in to view this page.'); window.location.href = '/index.php';</script>";
    exit();
}

if (isset($_GET['login']) && $_GET['login'] == 'success') {
    echo "<script>alert('You are signed in successfully.');</script>";
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
            <!-- <a class="nav-link" href="#" onclick="document.getElementById('yearSelectionForm').style.display='block'; return false;">Report</a> -->
            <a class="nav-link" href="/actions/report.php">Report</a>
        </li>
        <?php if (isset($_SESSION["admin_signed_in"])) {
            echo '<li class="nav-item"><a class="nav-link" href="/admin/users_list.php">User List</a></li>';
        }
        ?>
        <li class="nav-item"><a class="nav-link" href="../components/signout.php">Sign out</a></li>
    </ul>
  </div>
</nav>



<script>
// Optional: Automatically show the form when the page loads, if desired
// window.onload = function() { document.getElementById('yearSelectionForm').style.display='block'; };
</script>
