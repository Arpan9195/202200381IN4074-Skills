<?php
include 'config.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $style = $_POST['style'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    if ($_FILES["image"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_path = $target_file;

        $sql = "UPDATE products SET type='$type', style='$style', name='$name', description='$description', price='$price', image_path='$image_path' WHERE id=$id";
    } else {
        $sql = "UPDATE products SET type='$type', style='$style', name='$name', description='$description', price='$price' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$sql = "SELECT * FROM products WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f4;
            background-image: url('img/background2.png'); /* Path to your background image */
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .background-blur {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('img/background2.png') no-repeat center center;
            background-size: cover;
            filter: blur(8px);
            z-index: -1;
        }
        h2 {
            text-align: center;
            color: #333;
            margin: 40px 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
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
            padding: 14px;
            margin-bottom: 18px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        input[type="submit"]:active {
            background-color: #004494;
        }
        .image-preview {
            margin: 20px 0;
            text-align: center;
        }
        .image-preview img {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007bff;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="background-blur"></div>
    <h2>Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="type">Type:</label>
        <input type="text" name="type" value="<?php echo htmlspecialchars($row['type']); ?>" required>
        
        <label for="style">Style:</label>
        <input type="text" name="style" value="<?php echo htmlspecialchars($row['style']); ?>" required>
        
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
        
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>
        
        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
        
        <label for="image">Image:</label>
        <input type="file" name="image">
        
        <div class="image-preview">
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        </div>
        
        <input type="submit" value="Update Product">
    </form>
    <a href="index.php">Back to Product List</a>
</body>
</html>
