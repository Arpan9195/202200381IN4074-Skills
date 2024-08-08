<?php
include 'config.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["name"]. " - Price: " . $row["price"]. "<br>";
        echo "<img src='" . $row["image_path"] . "' alt='" . $row["name"] . "' style='width:100px;height:auto;'><br>";
        echo "<a href='edit_product.php?id=" . $row["id"] . "'>Edit</a> - <a href='delete_product.php?id=" . $row["id"] . "'>Delete</a><br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
