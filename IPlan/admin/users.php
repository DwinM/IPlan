<?php
// /admin/users.php
session_start();
require '../db/connection.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

// Handle delete user
if (isset($_GET['delete_id'])) {
    $user_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

$result = $conn->query("SELECT id, name, email FROM users");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h2>Users</h2>
<table border="1" cellpadding="5">
    <tr><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><a href="users.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
<?php $result->free(); ?>
