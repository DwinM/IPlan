<?php
session_start();

$serverName = "localhost"; // MySQL server is localhost in XAMPP
$dbUsername = "root"; // XAMPP default MySQL username is root
$dbPassword = ""; // Default no password, update if you set one
$database = "anhs"; // Database name that contains your Users table

// Create MySQL connection using mysqli
$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $database);
if ($conn) {
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate that both password fields match
    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit();
    }

    // Check if username or email already exists in the database
    $checkQuery = "SELECT * FROM users WHERE Username = ? OR Email = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Username or Email already exists. Please try a different one.";
        exit();
    }

    // If all checks pass, hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the Users table (First_Name and Last_Name added)
    $sql = "INSERT INTO users (Username, First_Name, Last_Name, Email, Password) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $first_name, $last_name, $email, $hashedPassword);

    // Execute the query and check if the insertion was successful
    if (mysqli_stmt_execute($stmt)) {
        echo "Sign-up successful! You can now <a href='Login.html'>log in</a>.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}


