<?php
// /admin/dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Welcome, Admin</h2>
<ul>
    <li><a href="add_book.php">Add New Book</a></li>
    <li><a href="list_books.php">Manage Books</a></li>
    <li><a href="users.php">View Users</a></li>
    <li><a href="borrowed_books.php">Borrowed Records</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</body>
</html>
