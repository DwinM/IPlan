<?php
// /admin/login.php
session_start();
require '../connection.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Prepared statement to select admin
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($admin_id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            // Successful login
            $_SESSION['admin_id'] = $admin_id;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../login.css">
</head>
<script>
    // JavaScript to handle tab switching
    function showForm(formId) {
        var loginForm = document.getElementById('login-form');
        var signupForm = document.getElementById('signup-form');
        var loginTab = document.getElementById('tab-1');
        var signupTab = document.getElementById('tab-2');

        if (formId === 'login-form') {
            loginForm.classList.add('active');
            signupForm.classList.remove('active');
            loginTab.classList.add('active-tab');
            signupTab.classList.remove('active-tab');
        } else {
            signupForm.classList.add('active');
            loginForm.classList.remove('active');
            signupTab.classList.add('active-tab');
            loginTab.classList.remove('active-tab');
        }
    }

    // Set default tab
    document.addEventListener("DOMContentLoaded", function () {
        showForm('login-form');
    });
</script>
<body>
    <div class="login-page">
        <div class="login-wrap">
            <div class="login-html">
                <!-- Sign In Tab -->
                <label id="tab-1" class="tab active-tab" onclick="showForm('login-form')">Sign In</label>
                <!-- Sign Up Tab -->
                <label id="tab-2" class="tab" onclick="showForm('signup-form')">Sign Up</label>

                <div class="login-form">
                    <!-- Sign In Form -->
                    <div id="login-form" class="sign-in-html active">
                        <form action="login.php" method="post">
                            <div class="group">
                                <label for="user" class="label">Username</label>
                                <input id="user" type="text" class="input" name="username">
                            </div>
                            <div class="group">
                                <label for="pass" class="label">Password</label>
                                <input id="pass" type="password" class="input" name="password" data-type="password">
                            </div>
                            <div class="group">
                                <input id="check" type="checkbox" class="check" checked>
                                <label for="check"><span class="icon"></span> Keep me Signed in</label>
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Sign In">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <a href="#forgot">Forgot Password?</a>
                            </div>
                        </form>
                    </div>

                    <!-- Sign Up Form -->
                    <div id="signup-form" class="sign-up-html">
                        <form action="register.php" method="post">
                            <div class="group">
                                <label for="user" class="label">Username</label>
                                <input id="user" type="text" class="input" name="name">
                            </div>
                            <div class="group">
                                <label for="first-name" class="label">First Name</label>
                                <input id="first-name" type="text" class="input" name="First_name">
                            </div>
                            <div class="group">
                                <label for="last-name" class="label">Last Name</label>
                                <input id="last-name" type="text" class="input" name="Last_name">
                            </div>
                            <div class="group">
                                <label for="pass" class="label">Password</label>
                                <input id="pass" type="password" class="input" name="password" data-type="password">
                            </div>
                            <div class="group">
                                <label for="email" class="label">Email Address</label>
                                <input id="email" type="text" class="input" name="email">
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Sign Up">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <label for="tab-1">Already a Member?</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
