<?php
// /admin/add_book.php
session_start();
require '../connection.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title']; 
    $author = $_POST['author']; 
    $description = $_POST['description']; 
    $quantity = (int)$_POST['quantity'];
    $stmt = $conn->prepare("INSERT INTO books (title, author, description, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $author, $description, $quantity);
    if ($stmt->execute()) {
        header('Location: list_books.php');
        exit;
    } else {
        $error = 'Error: Could not add book.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Add New Book</h2>
<form method="post">
    Title: <input type="text" name="title" required><br>
    Author: <input type="text" name="author" required><br>
    Description: <textarea name="description"></textarea><br>
    Quantity: <input type="number" name="quantity" required><br>
    <input type="submit" value="Add Book">
</form>
<p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
