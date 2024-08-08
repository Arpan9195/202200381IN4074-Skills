<?php
include 'config.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM products WHERE id=$delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - Alpha Design Home Decor</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        header {
            background-color: #f8f9fa;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        header .cart-icon img {
            width: 50px;
            height: auto;
        }
        nav {
            background-color: #343a40;
            padding: 10px 20px;
        }
        nav ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration: none;
            color: #fff;
        }
        .main {
            padding: 20px;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .actions a {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .actions a:hover {
            background-color: #218838;
        }
        .filter {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }
        .filter label, .filter select {
            margin: 0 10px;
        }
        .catalog {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product-entry {
            display: flex;
            flex-direction: column;
            width: 30%;
            margin: 1%;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .product-entry img {
            width: 100%;
            height: auto;
        }
        .product-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .product-info h2 {
            margin: 0 0 10px;
        }
        .product-info p {
            margin: 5px 0;
        }
        .product-info .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .product-info .buttons a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .product-info .buttons a.edit {
            background-color: #28a745; /* New color for Edit button */
            color: white;
            font-style: italic;
        }
        .product-info .buttons a.delete {
            background-color: #dc3545; /* New color for Delete button */
            color: white;
            font-style: italic;
        }
        .product-info .buttons a:hover {
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            .product-entry {
                width: 45%;
            }
        }
        @media (max-width: 576px) {
            .product-entry {
                width: 100%;
            }
            .filter {
                flex-direction: column;
            }
            .filter label, .filter select {
                margin: 5px 0;
            }
        }
    </style>
    <script>
        function filterProducts() {
            const typeFilter = document.getElementById('type-filter').value;
            const styleFilter = document.getElementById('style-filter').value;

            const products = document.querySelectorAll('.product-entry');

            products.forEach(product => {
                const type = product.getAttribute('data-type');
                const style = product.getAttribute('data-style');

                if ((typeFilter === 'all' || type === typeFilter) && (styleFilter === 'all' || style === styleFilter)) {
                    product.style.display = 'flex';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            filterProducts();
        });
    </script>
</head>
<body>
    <header>
        <h1>Product Catalog</h1>
        <a href="addtocart.html" class="cart-icon">
            <img src="img/cart.jpg" alt="Cart">
        </a>
    </header>
    <nav>
        <ul>
            <li><a href="shop.html">Home</a></li>
            <li><a href="design.html">Design Gallery</a></li>
            <li><a href="index.php">Product Catalog</a></li>
        </ul>
    </nav>
    <main class="main">
        <div class="actions">
            <h2>Product List</h2>
            <a href="create_product.php">Create New Product</a>
        </div>
        <div class="filter">
            <label for="type-filter">Filter by Type:</label>
            <select id="type-filter" onchange="filterProducts()">
                <option value="all">All</option>
                <option value="furniture">Furniture</option>
                <option value="rugs">Rugs</option>
                <option value="lamps">Lamps</option>
                <option value="wall-art">Wall Art</option>
            </select>

            <label for="style-filter">Filter by Style:</label>
            <select id="style-filter" onchange="filterProducts()">
                <option value="all">All</option>
                <option value="modern">Modern</option>
                <option value="traditional">Traditional</option>
                <option value="industrial">Industrial</option>
                <option value="bohemian">Bohemian</option>
            </select>
        </div>

        <div class="catalog" id="catalog">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product-entry' data-type='" . $row['type'] . "' data-style='" . $row['style'] . "'>";
                    echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
                    echo "<div class='product-info'>";
                    echo "<h2>" . $row['name'] . "</h2>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p><strong>Price: $" . $row['price'] . "</strong></p>";
                    echo "<div class='buttons'>";
                    echo "<a href='edit_product.php?id=" . $row['id'] . "' class='edit'>Edit</a>";
                    echo "<a href='index.php?delete_id=" . $row['id'] . "' class='delete'>Delete</a>";
                    echo "</div></div></div>";
                }
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>