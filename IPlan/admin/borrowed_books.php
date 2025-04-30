<?php
// /admin/borrowed_books.php
session_start();
require '../db/connection.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

// Join borrowed_books with users and books
$sql = "SELECT b.id, u.name AS user, bo.title AS book, b.borrow_date, b.due_date, b.fine_paid
        FROM borrowed_books b
        JOIN users u ON b.user_id = u.id
        JOIN books bo ON b.book_id = bo.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Records</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Borrowed Books Records</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>User</th><th>Book</th><th>Borrow Date</th><th>Due Date</th><th>Fine Paid</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()):
        $status = $row['fine_paid'] ? 'Yes' : 'No';
    ?>
    <tr>
        <td><?= htmlspecialchars($row['user']) ?></td>
        <td><?= htmlspecialchars($row['book']) ?></td>
        <td><?= $row['borrow_date'] ?></td>
        <td><?= $row['due_date'] ?></td>
        <td><?= $status ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
<?php $result->free(); ?>
