<?php
include 'config.php';

// Check if the 'uri' parameter exists in the URL
if (isset($_GET['uri']) && !empty($_GET['uri'])) {
    $uri = filter_var($_GET['uri'], FILTER_SANITIZE_STRING);

    // Retrieve user data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE uri = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $uri);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['name'] ?? '';
        $last_donation = $row['ld'] ?? '';
        $city = $row['district'] ?? '';
        $imgpae = !empty($row['profile']) ? $row['profile'] : 'ab.png';
        $blood_group = $row['blood_group'] ?? '';
        $thana = $row['thana'] ?? '';
        $number = $row['num'] ?? '';
    } else {
        die("No user found with the specified URI.");
    }
    $stmt->close();
} else {
    die("Invalid or missing URI. Please check the URL.");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'];
    $newLastDonation = $_POST['last_donation'];
    $newCity = $_POST['city'];
    $newBloodGroup = $_POST['blood_group'];
    $newThana = $_POST['thana'];
    $newNumber = $_POST['number'];

    // Define upload directory
    $targetDir = "imgp/";

    // Ensure the directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory with necessary permissions
    }

    $profileImage = $imgpae; // Default to the existing profile image

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['profile_image']['name']);
        $uniqueName = uniqid() . "_" . $fileName;
        $targetFilePath = $targetDir . $uniqueName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                $profileImage = $targetFilePath; // Save the uploaded file path
            } else {
                echo "Error uploading the profile image.";
            }
        } else {
            echo "Invalid file type. Please upload an image (JPG, JPEG, PNG, GIF).";
        }
    }

    // Update the database
    $sql = "UPDATE users
            SET name = ?, 
                ld = ?, 
                district = ?, 
                blood_group = ?, 
                thana = ?, 
                num = ?, 
                profile = ?
            WHERE uri = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssss",
        $newName,
        $newLastDonation,
        $newCity,
        $newBloodGroup,
        $newThana,
        $newNumber,
        $profileImage,
        $uri
    );

    if ($stmt->execute()) {
        header("Location:{$hostname}/profile.php?uri=$uri");
    } else {
        die("Execution failed: " . $stmt->error);
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .edit-form {
            max-width: 500px;
            width: 100%;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .circle-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 10px auto;
            border: 3px solid #d32f2f;
        }

        .edit-form input,
        .edit-form select,
        .edit-form button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .edit-form button {
            background-color: #d32f2f;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        #image-container {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }

        #image-preview {
            max-width: 100%;
            max-height: 400px;
        }

        #crop-btn {
            background-color: #d32f2f;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>

<body>
    <form class="edit-form" method="post" enctype="multipart/form-data">
        <div>
            <img id="preview" src="<?php echo htmlspecialchars($imgpae); ?>" alt="Profile Preview" class="circle-preview">
            <label for="profile_image">
                <i class="fas fa-camera"></i>
            </label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;">
        </div>
        <input type="hidden" id="cropped-image" name="cropped_image">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" required>
        <label for="last_donation">Last Donation (YYYY-MM-DD):</label>
        <input type="date" id="last_donation" name="last_donation" value="<?php echo htmlspecialchars($last_donation); ?>">
        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
        <label for="blood_group">Blood Group:</label>
        <select id="blood_group" name="blood_group" required>
            <option value="<?php echo htmlspecialchars($blood_group); ?>"><?php echo htmlspecialchars($blood_group); ?></option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>
        <label for="district">District:</label>
        <select id="district" name="district" required>
            <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
            <option value="CTG">Chattogram</option>
            <option value="DHAKA">Dhaka</option>
            <option value="RAJ">Rajshahi</option>
        </select>
        <label for="thana">Thana:</label>
        <input type="text" id="thana" name="thana" value="<?php echo htmlspecialchars($thana); ?>" required>
        <label for="number">Phone Number:</label>
        <input type="tel" id="number" name="number" pattern="01[3-9][0-9]{8}" value="<?php echo htmlspecialchars($number); ?>" required>
        <button type="submit">Save Changes</button>
    </form>
    <div id="image-container">
        <img id="image-preview" alt="Crop Preview">
        <button id="crop-btn">Crop</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const profileImageInput = document.getElementById('profile_image');
        const imagePreview = document.getElementById('image-preview');
        const imageContainer = document.getElementById('image-container');
        const croppedImageInput = document.getElementById('cropped-image');
        const previewElement = document.getElementById('preview');
        const cropButton = document.getElementById('crop-btn');

        profileImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    imagePreview.src = reader.result;
                    imageContainer.style.display = 'block';
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(imagePreview, {
                        aspectRatio: 1,
                        viewMode: 2,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        cropButton.addEventListener('click', () => {
            const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
            canvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                previewElement.src = url;
                croppedImageInput.value = canvas.toDataURL();
                imageContainer.style.display = 'none';
                cropper.destroy();
            });
        });
    </script>
</body>

</html>
