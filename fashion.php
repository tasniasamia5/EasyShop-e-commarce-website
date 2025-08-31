<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fashion Mart - EasyShop</title>
    <link rel="icon" type="image/png" href="images/logo2.jpg">
    <link rel="stylesheet" href="fashion.css"> <!-- Reuse styling -->
    <link rel="stylesheet" href="header.css">   <!-- Global style and header -->
</head>
<body>

<?php include 'header.php'; ?>

<h1>Fashion Mart</h1>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_category = "fashion"; // Set category to 'fashion'
$sql = "SELECT * FROM products WHERE category='$current_category'";
$result = $conn->query($sql);

echo '<div class="product-detail-container">';

while ($row = $result->fetch_assoc()) {
    echo '<div class="product-card">';
    echo '<img src="' . $row['image'] . '" alt="' . htmlspecialchars($row['name']) . '">';
    echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '<p>' . htmlspecialchars($row['price']) . ' Tk</p>';
    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
    echo '<form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="' . $row['id'] . '">
            <input type="hidden" name="product_name" value="' . htmlspecialchars($row['name']) . '">
            <input type="hidden" name="product_price" value="' . $row['price'] . '">
            <button type="submit">Add to Cart</button>
          </form>';
    echo '</div>';
}

echo '</div>';

$conn->close();
?>

</body>
</html>
