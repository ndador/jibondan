<?php
session_start();
include 'config.php';
if (isset($_SESSION["uri"])) {
  $uri = $_SESSION["uri"];
    $row1 = "?uri=" . $uri;

    $stmt = $conn->prepare("SELECT * FROM users WHERE uri = ?");
    $stmt->bind_param("s", $uri);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        // Fetch user data if needed
    }else
{
    echo 'by';
}    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Clone Navbar</title>
    <!-- Font Awesome CDN Link -->
     <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>

    <nav class="navbar">
    <div class="logo">
        <div class="j">J</div>
        <div class="text">
            <span class="red">IBON</span>
            <span class="green">DAN</span>
        </div>
    </div>
    <ul class="nav-links">
            <li><a href="index.php<?php  echo ($row1)?>"><i class="fas fa-home"></i></a></li>
            <li><a href="#"><i class="fas fa-user-friends"></i></a></li>
            <li><a href="#"><i class="fas fa-tv"></i></a></li>
            <li><a href="#"><i class="fas fa-store"></i></a></li>
            <li><a href="#"><i class="fas fa-users"></i></a></li>
            <li><a href="#"><i class="fas fa-gamepad"></i></a></li>
            <li><a href="#"><i class="fas fa-bell"></i></a></li>
            <li><a href="#"><i class="fas fa-user"></i></a></li>
        </ul>
        <div class="profile">
          <a href="profile.php<?php  echo ($row1)?>"> <img src="https://via.placeholder.com/30" alt="Profile Picture">
            <span class="menu-icon"><i class="fas fa-bars"></i></span></a>
        </div>
    </nav>

    <script>
        // Toggle the menu visibility on smaller screens
        const menuIcon = document.querySelector('.menu-icon');
        const navLinks = document.querySelector('.nav-links');

        menuIcon.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>

</body>
</html>