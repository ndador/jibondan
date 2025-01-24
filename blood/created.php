<?php
session_start();
require 'config.php'; // Ensure this includes the correct database connection.

if (isset($_SESSION["uri"])) {
    $uri = $_SESSION["uri"];
    header("Location: {$hostname}/index.php");
    exit();
}

function generateUniqueURI($user_id) {
    return "{$user_id}-" . bin2hex(random_bytes(8));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $number = $_POST['number'] ?? '';
    $bloodGroup = $_POST['bloodGroup'] ?? '';
    $district = $_POST['district'] ?? '';
    $thana = $_POST['category'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($number) || empty($bloodGroup) || empty($district) || empty($thana) || empty($password)) {
        die('Please fill out all fields.');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $uri = generateUniqueURI($name);

    $sql = "INSERT INTO users (name, num, blood_group, district, thana, pass, uri) 
            VALUES ('$name', '$number', '$bloodGroup', '$district', '$thana', '$hashedPassword', '$uri')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $name;
        $_SESSION['uri'] = $uri;
        header("Location:{$hostname}/profile.php?uri=$uri"); 
        exit();
    } else {
        die('Error: ' . $connection->error);
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container-wrapper">
        <div>
            <img src="logo.png" alt="Logo">
            <h1 style="font-size: 27px;">রক্তদান জীবন দান: মানবতার এক চরম নিদর্শন</h1>
            <p>রক্তদান জীবন দান" শুধুমাত্র একটি বাক্য নয়; এটি মানবতার প্রতি আমাদের দায়িত্ব এবং ভালোবাসার এক উজ্জ্বল উদাহরণ।</p>
        </div>
        <div class="container">
          

            <!-- Multi-Step Form -->
            <form id="multiStepForm" action="" method="POST">
                <h2 style="text-align: center;">Create Account</h2>
                <div class="form-step active">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="number">Number:</label>
                        <input type="text" id="number" name="number" required>
                    </div>
                    <div class="form-group">
                        <label for="bloodGroup">Blood Group:</label>
                        <select id="bloodGroup" name="bloodGroup" required>
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>

                <div class="form-step">
                <div class="form-group">
    <label for="district">District:</label>
    <select id="district" name="district" required>
        <option value="">Select a District</option>
        <option value="CTG">Chattogram</option>
        <option value="DHAKA">Dhaka</option>
        <option value="RAJ">Rajshahi</option>
    </select>
</div>
<div class="form-group">
    <label for="category">Thana:</label>
    <select id="category" name="category" required>
        <option value="">Select a Thana</option>
    </select>
</div>
                </div>

                <div class="form-step">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
<span class="toggle-password" onclick="togglePassword()">🙈</span>
                    </div>
                </div>

                <div class="navigation-buttons">
                    <button type="button" id="prevBtn" onclick="changeStep(-1)" style="display: none;">Previous</button>
                    <button type="button" id="nextBtn" onclick="changeStep(1)">Next</button>
                    <button type="submit" id="submitBtn" style="display: none;">Submit</button>
                </div>
                <a class="toggle-link" href="login.php">Already have an account? Login</a>
            </form>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>
</html>