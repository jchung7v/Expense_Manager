<?php
session_start();

// If the admin is already signed in, redirect to the users list page
if(isset($_SESSION["admin_signed_in"]) && $_SESSION["admin_signed_in"] === true){
    header("location: ./users_list.php");
    exit;
}

if (isset($_POST['Admin-signin'])) {
    include("../components/utils.php");
    include("../connect_database.php");

    $adminEmail = sanitize_input($_POST['Email']);
    $adminPassword = sanitize_input($_POST['Password']);

    $checkStmt = $db->prepare("SELECT * FROM Admins WHERE Email = ?");
    $checkStmt->bindParam(1, $adminEmail, SQLITE3_TEXT);
    $result = $checkStmt->execute();

    if ($result === false) {
        echo "<script>alert('Error signing in'); window.location.href = '/';</script>";
        exit();
    } else {
        $admin = $result->fetchArray(SQLITE3_ASSOC);
        if ($admin && password_verify($adminPassword, $admin['Password'])) {
            $_SESSION['admin_signed_in'] = true;
            $_SESSION['admin_role'] = $admin['Role'];
            $_SESSION['admin_id'] = $admin['AdminId'];
            $_SESSION['admin_email'] = $admin['Email'];
            header('Location: users_list.php');
            
        } else {
            echo "<script>alert('Invalid email or password'); window.location.href = '/';</script>";
            exit();
        }
    }

    $db->close();
}
include '../inc_header.php';
?>

<h1>Admin Sign-in</h1>

<div class="row">
<div class="col-md-6 col-lg-3 mx-auto">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="Email" class="control-label">Email</label>
                <input for="Email" class="form-control" name="Email" id="Email" required />
                <div id="EmailWarning" style="color: red;"></div>            
            </div>

            <div class="form-group mt-2">
                <label for="Password" class="control-label">Password</label>
                <input for="Password" class="form-control" name="Password" id="Password" required/>
            </div>

            <div class="form-group mt-3">
                <a href="../index.php">Back to a user sign in page</a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="Sign in" name="Admin-signin" class="btn btn-primary" />
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