<?php
session_start();

// If user is already signed in, redirect to main page
if(isset($_SESSION["user_signed_in"]) && $_SESSION["user_signed_in"] === true){
    header("location: ../main.php");
    exit;
}

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo "<script>alert('You are signed out successfully');</script>";
}

if (isset($_POST['User-signin'])) {
    // the location of other components is set by index.php, not user_signin.php
    include("./components/utils.php");
    include("./connect_database.php");

    $userEmail = sanitize_input($_POST['Email']);
    $userPassword = sanitize_input($_POST['Password']);

    $checkStmt = $db->prepare("SELECT * FROM Users WHERE Email = ?");
    $checkStmt->bindParam(1, $userEmail, SQLITE3_TEXT);
    $result = $checkStmt->execute();

    if ($result === false) {
        echo "<script>alert('Error signing in'); window.location.href = '../index.php';</script>";
        exit();
    } else {
        $user = $result->fetchArray(SQLITE3_ASSOC);
        if ($user && password_verify($userPassword, $user['Password'])) {
            if ($user['Status'] == 1) {
                $_SESSION['user_signed_in'] = true;
                $_SESSION['user_role'] = "user";
                $_SESSION['user_id'] = $user['UserId'];
                $_SESSION['user_email'] = $user['Email'];
                $_SESSION['user_status'] = $user['Status'];
                header('Location: ../main.php?login=success');
                exit();
            }
            else {
                echo "<script>alert('The user is not authorized. If you are an administrator, please sign in through the Admin Sign-in page.'); window.location.href = '../index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href = '../index.php';</script>";
            exit();
        }
    }

    $db->close();
}
?>

<h1>Sign-in</h1>

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
                <a href="./registration/registration.php">Create new account</a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="Sign in" name="User-signin" class="btn btn-primary" />
            </div>
        </form>
        <hr> <!-- Visual divider -->
        <div class="mt-2"> <!-- Margin top for spacing -->
            <a href="./admin/admin_signin.php">Admin Sign in</a>
        </div>
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