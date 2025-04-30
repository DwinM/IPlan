<?php
// /admin/list_books.php
session_start();
require '../db/connection.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

// Handle delete action
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    $stmt->close();
    header('Location: list_book.php');
    exit;
}

// Fetch all books
$result = $conn->query("SELECT * FROM books");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Books</h2>
<a href="add_book.php">Add New Book</a><br><br>
<table border="1" cellpadding="5">
    <tr><th>Title</th><th>Author</th><th>Qty</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['author']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td>
            <a href="edit_book.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="list_books.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
<?php $result->free(); ?>
