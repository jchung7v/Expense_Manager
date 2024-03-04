<?php include '../inc_header.php'; ?>

<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']);
}
?>

<h1>Admin Sign-in</h1>

<div class="row">
    <div class="col-md-4">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="admin_process.php" method="post">
            <div class="form-group">
                <label for="Email" class="control-label">Email</label>
                <input for="Email" class="form-control" name="Email" id="Email" required />
                <div id="EmailWarning" style="color: red;"></div>            
            </div>

            <div class="form-group">
                <label for="Password" class="control-label">Password</label>
                <input for="Password" class="form-control" name="Password" id="Password" required/>
            </div>

            <div class="form-group">
                <a href="../index.php">Back to sign in page</a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="SignIn" name="Admin-signin" class="btn btn-primary" />
            </div>
        </form>
    </div>
</div>

<script>
function validateEmail() {
    var email = document.getElementById("Email").value;
    var emailWarning = document.getElementById("emailWarning");
    var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (!regex.test(email)) {
        emailWarning.textContent = "Invalid email format";
        return false;
    } else {
        emailWarning.textContent = "";
        return true;
    }
}

function validateForm() {
    return validateEmail();
}
</script>

<?php include '../inc_footer.php'; ?>