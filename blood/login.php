<?php
session_start();

// Include the configuration file
require 'config.php'; // Ensure this file initializes the database connection ($conn)

// Redirect if the user is already logged in
if (isset($_SESSION["uri"])) {
    header("Location: {$hostname}/profile.php");
    exit();
}

$error_message = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $name = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->bind_param("s", $name); // "s" specifies the type as string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch the user's data
$uri= $row['uri'];
            // Verify the password
            if (password_verify($password, $row['pass'])) {
                // Set session variables
                $_SESSION['uri'] = $uri;
                $_SESSION['username'] = $row['name'];
                $_SESSION['id'] = $row['id'] ?? null; // Assuming an `id` column exists

                // Redirect to profile page
                header("Location:{$hostname}/profile.php?uri=$uri"); 
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "User does not exist.";
        }
    } catch (Exception $e) {
        // Handle any errors (e.g., database connection issues)
        $error_message = "An error occurred: " . $e->getMessage();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login2.css">
</head>
<body>
    <div class="container-wrapper">
        <div class="header">
            <img src="logo.png" alt="Logo">
            <h1>রক্তদান জীবন দান: মানবতার এক চরম নিদর্শন</h1>
            <p>রক্তদান মানবতার প্রতি আমাদের দায়িত্ব এবং ভালোবাসার এক উজ্জ্বল উদাহরণ।</p>
        </div>
        <div class="container">
            <div class="login-container">
                <h2 class="login-header">Login</h2>
                <form action="" method="POST">
                    <?php if (!empty($error_message)) { ?>
                        <div class="error-message"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login">Login</button>
                </form>
                <a class="toggle-link" href="created.php">Already have an account? Login</a>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>
</html>
