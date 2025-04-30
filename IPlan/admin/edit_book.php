<?php
// /admin/edit_book.php
session_start();
require '../db/connection.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

if (!isset($_GET['id'])) {
    header('Location: list_books.php');
    exit;
}
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT title, author, description, quantity FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($title, $author, $description, $quantity);
$stmt->fetch();
$stmt->close();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title']; $author = $_POST['author'];
    $description = $_POST['description']; $quantity = (int)$_POST['quantity'];
    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, description=?, quantity=? WHERE id=?");
    $stmt->bind_param("sssii", $title, $author, $description, $quantity, $id);
    if ($stmt->execute()) {
        header('Location: list_books.php');
        exit;
    } else {
        $error = 'Error: Could not update.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Edit Book</h2>
<form method="post">
    Title: <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required><br>
    Author: <input type="text" name="author" value="<?= htmlspecialchars($author) ?>" required><br>
    Description: <textarea name="description"><?= htmlspecialchars($description) ?></textarea><br>
    Quantity: <input type="number" name="quantity" value="<?= $quantity ?>" required><br>
    <input type="submit" value="Update Book">
</form>
<p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
