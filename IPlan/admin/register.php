<?php
// /admin/register.php
session_start();
require '../connection.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Hash the password for security
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepared statement to insert new admin
    $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hash);
    if ($stmt->execute()) {
        header('Location: login.php');
        exit;
    } else {
        $error = 'Error: Could not create admin.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Register New Admin</h2>
<form method="post">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Register">
</form>
<p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
