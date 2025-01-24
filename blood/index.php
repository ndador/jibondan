<?php
include 'config.php';
$sql = "SELECT * FROM `slide`";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output each icon data dynamically
                while($row = $result->fetch_assoc()) {
     $text= $row['tect'];
                }
            }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Responsive Icon Page</title>
</head>
<body>
    <?php require 'nav.php'; ?>
    <header class="banner">
        <!-- You can add content here for the banner or remove it if unnecessary -->
    </header>

    <div class="sliding-text-container">
        <div class="sliding-text">
            <div><?php echo $text; ?></div>
        </div>
    </div>

    <div class="box-container">
        <div class="icon-container">
            <div class="icon-item">
                <i class="fas fa-tint"></i>
                <p>Blood List</p>
            </div>
            <div class="icon-item">
                <i class="fas fa-search"></i>
                <p>Search</p>
            </div>
            <div class="icon-item">
                <i class="fas fa-hand-holding-droplet"></i>
                <p>Request</p>
            </div>
            <div class="icon-item">
                <i class="fas fa-hand-holding-droplet"></i>
                <p>Request</p>
            </div>
            <div class="icon-item">
                <i class="fas fa-hand-holding-droplet"></i>
                <p>Request</p>
            </div>
            <div class="icon-item">
                <i class="fas fa-hand-holding-droplet"></i>
                <p>Request</p>
            </div>
        </div>
    </div>

</body>
</html>
