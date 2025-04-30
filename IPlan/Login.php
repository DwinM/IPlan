<?php
session_start();

// MySQL Server connection settings for XAMPP
$serverName = "localhost"; // XAMPP MySQL server is localhost
$username = "root"; // Default MySQL username for XAMPP is root
$password = ""; // Leave this empty unless you've set a MySQL root password in XAMPP
$database = "anhs"; // The database name you created

// Create MySQL connection using mysqli
$conn = mysqli_connect($serverName, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // If connection fails, output an error
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input from the form
    $usernameOrEmail = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Ensure both fields are not empty
    if (empty($usernameOrEmail) || empty($password)) {
        echo "Username/Email and password cannot be empty.";
    } else {
        // Check if input is a valid email format
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            // SQL query to search for user by email
            $sql = "SELECT * FROM users WHERE Email = ?";
        } else {
            // SQL query to search for user by username
            $sql = "SELECT * FROM users WHERE Username = ?";
        }

        // Prepare the SQL statement
        $stmt = mysqli_prepare($conn, $sql);

        // Bind the input (username/email) to the query
        mysqli_stmt_bind_param($stmt, "s", $usernameOrEmail);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Fetch the result
        $result = mysqli_stmt_get_result($stmt);

        // Check if a row was returned
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify the password (the stored password is hashed)
            if (password_verify($password, $row['Password'])) {
                // Password matches, set session and redirect to welcome page
                $_SESSION['UserID'] = $row['UserID']; 
                $_SESSION['username'] = $row['Username'];
                header("Location: index.php");
                exit(); // Ensure the script stops after redirection
            } else {
                // Incorrect password
                echo "Invalid username/email or password.";
            }
        } else {
            // No user found
            echo "Invalid username/email or password.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

// Close the MySQL connection
mysqli_close($conn);

