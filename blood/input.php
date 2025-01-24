<?php
include 'config.php';

// Initialize variables to store the city and thana data
$cities = [];

// Fetch the cities from the database
$result = $conn->query("SELECT * FROM city");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = ['id' => $row['id'], 'city' => $row['city']];
    }
}

// Handle form submissions for the city and thana
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert city if provided
    if (isset($_POST['city']) && !empty($_POST['city'])) {
        $city = $_POST['city'];

        // Insert city into the database
        $sql = "INSERT INTO `city` (`city`) VALUES ('$city')";
        if ($conn->query($sql) === TRUE) {
            echo "New city added successfully!";
            // Redirect after success
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Insert thana if provided
    if (isset($_POST['thana']) && !empty($_POST['thana'])) {
        $thana = $_POST['thana'];
        // Insert thana into the database or handle accordingly
        echo "Thana: " . htmlspecialchars($thana);
    }
}

// Don't forget to close the connection after using it!
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City and Thana Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 300px;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form {
            margin: 10px 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 12px;
            font-size: 16px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- City Form -->
        <form id="cityForm" class="form" method="post">
            <label for="city">Enter City:</label>
            <input type="text" id="city" name="city" placeholder="Enter City">
            <button type="submit">Input City</button>
        </form>

        <!-- Thana Form -->
        <form id="thanaForm" class="form" method="post">
            <label for="thana">Select Thana:</label>
            <!-- Dynamic City Dropdown -->
            <label for="citySelect">Select City:</label>
            <select id="citySelect" name="citySelect" onchange="updateThanaInput()">
                <option value="">Select City</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['id']; ?>"><?= htmlspecialchars($city['city']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="thana">Thana:</label>
            <input type="text" id="thana" name="thana" value="" placeholder="Enter Thana" readonly>

            <button type="submit">Input Thana</button>
        </form>
    </div>

    <script>
        function updateThanaInput() {
            var citySelect = document.getElementById('citySelect');
            var thanaInput = document.getElementById('thana');
            var selectedCityId = citySelect.value;

            // Pre-fill the thana field based on the selected city (you can customize this logic)
            if (selectedCityId) {
                if (selectedCityId == '1') {
                    thanaInput.value = 'Dhanmondi';  // Example Thana
                } else if (selectedCityId == '2') {
                    thanaInput.value = 'Gulshan';    // Example Thana
                } else if (selectedCityId == '3') {
                    thanaInput.value = 'Uttara';     // Example Thana
                }
                thanaInput.readOnly = false;  // Allow the user to modify if needed
            } else {
                thanaInput.value = '';  // Reset if no city is selected
                thanaInput.readOnly = true;  // Make thana field read-only
            }
        }
    </script>
</body>
</html>
