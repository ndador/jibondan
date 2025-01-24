<?php
include 'config.php';

// Check if the 'uri' parameter exists in the URL
if (isset($_GET['uri']) && !empty($_GET['uri'])) {
    // Sanitize the input to prevent SQL injection
    $uri = filter_var($_GET['uri'], FILTER_SANITIZE_STRING);

    // Use a prepared statement to fetch the user's data securely
    $stmt = $conn->prepare("SELECT * FROM users WHERE uri = ?");
    $stmt->bind_param("s", $uri); // Bind the parameter as a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch the user's data
        $row = $result->fetch_assoc();
        $username = $row['name'] ?? 'Unknown User';
        $last_donation = !empty($row['ld']) ? $row['ld'] : 'Not Available';
        $cityo = $row['district'] ?? 'Not Specified';
        $imgpae = !empty($row['profile']) ? $row['profile'] : 'ab.png';
        $blood_group = $row['blood_group'] ?? 'Not Specified';
        $thana = $row['thana'] ?? 'Not Specified';
        $number = $row['num'] ?? 'Not Available';
    } else {
        // Handle the case where no matching record is found
        die("No user found with the specified URI.");
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case where the 'uri' parameter is missing or invalid
    die("Invalid or missing URI. Please check the URL.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="pro.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
        crossorigin="anonymous" referrerpolicy="no-referrer">
  <style>
  body {
  font-family: Arial, sans-serif;
  background-color: #f5f5f5;
  margin: 0;
  padding: 0;
}

.profile-container {
  width: 80%;
  margin: 50px auto;
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-header {
  display: flex;
  align-items: center;
  gap: 20px;
}

.profile-header img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #d32f2f;
}

.profile-header h1 {
  margin: 0;
  font-size: 2em;
  text-transform: capitalize;
  font-family: serif;
}

.profile-details {
  margin-top: 20px;
}

.profile-details div {
  margin: 10px 0;
  font-size: 18px;
  display: flex;
  align-items: center;
}

.profile-details i {
  margin-right: 10px;
  color: #d32f2f;
  font-size: 24px; /* Same size for all icons */
}

.blood-group {
  color: #d32f2f;
  font-size: 1.2em;
  margin: 0;
  text-transform: capitalize;
}

.contact-number {
  text-decoration: underline;
  cursor: pointer;
}

.whatsapp-icon {
  margin-left: 10px;
  color: #25D366;
  font-size: 24px; /* Same size for the WhatsApp icon */
  text-decoration: none;
}

.whatsapp-icon:hover {
  color: #1ebe58;
}

  </style>
</head>
<body>

<div class="profile-container">
  <div class="profile-header">
    <img src="<?php echo htmlspecialchars($imgpae); ?>" alt="Profile Image">
    <div>
      <h1><?php echo htmlspecialchars($username); ?></h1>
      <a href="profile_edit.php?uri=<?php echo urlencode($uri); ?>">
        <button style="padding: 10px 20px; background-color: #d32f2f; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 7px 0;">
          Edit Profile
        </button>
      </a>
    </div>
  </div>
  <div class="profile-details">
    <div>
      <i class="fas fa-tint"></i> Blood Group: <span class="blood-group"> (<?php echo htmlspecialchars($blood_group); ?>)</span>
    </div>
    <div>
      <i class="fas fa-calendar-alt"></i> Last Donation: <span class="blood-group">  <?php echo htmlspecialchars($last_donation); ?></span>
    </div>
    <div>
      <i class="fas fa-map-marker-alt"></i> Thana: <span class="blood-group">   <?php echo htmlspecialchars($thana); ?></span>
    </div>
    <div>
      <i class="fas fa-map-marker-alt"></i> City: <span class="blood-group">    <?php echo htmlspecialchars($cityo); ?></span>
    </div>
    <div>
      <i class="fab fa-whatsapp"></i> Contact Number: 
      <a href="https://wa.me/<?php echo urlencode('+88' . $number); ?>" class="contact-number">
    <?php echo htmlspecialchars($number); ?>
</a>

    </div>
  </div>
</div>

</body>
</html>
