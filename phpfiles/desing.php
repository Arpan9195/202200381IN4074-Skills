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
    <title>Design Gallery - Alpha Design Home Decor</title>
    <link rel="stylesheet" href="design_gallery.css">
    <style>
        /* Your CSS here */
    </style>
    <script>
        function filterDesigns() {
            const roomFilter = document.getElementById('room-filter').value;
            const colorFilter = document.getElementById('color-filter').value;

            const designs = document.querySelectorAll('.design-entry');

            designs.forEach(design => {
                const room = design.getAttribute('data-room');
                const color = design.getAttribute('data-color');

                if ((roomFilter === 'all' || room === roomFilter) && (colorFilter === 'all' || color === colorFilter)) {
                    design.style.display = 'flex';
                } else {
                    design.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            filterDesigns();
        });
    </script>
</head>
<body>
    <header>
        <h1>Design Gallery</h1>
        <a href="addtocart.php" class="cart-icon">
            <img src="img/cart.jpg" alt="Cart">
            <!-- Notification div -->
            <div id="notification"></div>

            <script>
                function addToCart(itemName, cost) {
                    // Simulate adding the item to the cart
                    const notification = document.getElementById('notification');
                    notification.innerText = `${itemName} has been added to your cart.`;
                    notification.style.display = 'block';

                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                }
            </script>
        </a>
    </header>
    <nav>
        <ul>
            <li><a href="shop.php">Home</a></li>
            <li><a href="design_gallery.php">Design Gallery</a></li>
            <li><a href="index.php">Product Catalog</a></li>
        </ul>
    </nav>
    <main>
        <div class="filter">
            <label for="room-filter">Filter by Room:</label>
            <select id="room-filter" onchange="filterDesigns()">
                <option value="all">All</option>
                <option value="living-room">Living Room</option>
                <option value="bedroom">Bedroom</option>
                <option value="kitchen">Kitchen</option>
            </select>

            <label for="color-filter">Filter by Color Scheme:</label>
            <select id="color-filter" onchange="filterDesigns()">
                <option value="all">All</option>
                <option value="neutral">Neutral</option>
                <option value="warm">Warm</option>
                <option value="cool">Cool</option>
                <option value="vibrant">Vibrant</option>
                <option value="monochrome">Monochrome</option>
            </select>
        </div>

        <div class="gallery" id="gallery">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='design-entry' data-room='" . $row['room'] . "' data-color='" . $row['color'] . "'>";
                    echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
                    echo "<div class='design-info'>";
                    echo "<h2>" . $row['name'] . "</h2>";
                    echo "<p>By Designer Name</p>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p><strong>Cost: $" . $row['price'] . "</strong></p>";
                    echo "<button onclick=\"addToCart('" . $row['name'] . "', " . $row['price'] . ")\">Add to Cart</button>";
                    echo "<button class='move-to-wishlist' onclick=\"moveToWishlist('" . $row['name'] . "')\">Move to Wishlist</button>";
                    echo "</div></div>";
                }
            } else {
                echo "No designs available.";
            }
            ?>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>
