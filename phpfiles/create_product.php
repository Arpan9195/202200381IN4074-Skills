<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection setup
include 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $type = $_POST['type'];
    $style = $_POST['style'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Initialize image path
    $image_path = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";

        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid format
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.<br>";
            $uploadOk = 0;
        }

        // Check file size (5MB max)
        if ($_FILES["image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
        // If everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
                echo "The file ". basename($_FILES["image"]["name"]). " has been uploaded.<br>";
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }
    } else {
        echo "No file uploaded or file upload error.<br>";
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO products (type, style, name, description, price, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error . "<br>");
    }
    $stmt->bind_param("ssssds", $type, $style, $name, $description, $price, $image_path);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully.<br>";
        // Redirect to index page after successful insertion
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Alpha Design Home Decor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #e9ecef;
            background-image: url('img/background.png'); /* Updated image path */
            background-size: cover; /* Optional: makes the background cover the entire area */
            background-position: center; /* Optional: centers the background image */
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #f8b400;
            position: relative;
        }
        h1::before {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            width: 50px;
            height: 2px;
            background-color: #f8b400;
            transform: translateX(-50%);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #f8b400;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #f8a300;
        }
        input[type="submit"]:active {
            background-color: #f89700;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Product</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required>

            <label for="style">Style:</label>
            <input type="text" id="style" name="style" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" required>

            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
