<?php
session_start();
include 'includes/db.php';

$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products - EasyShop</title>
    <link rel="icon" type="image/png" href="images/logo2.jpg">
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
<h1>All Products</h1>
<div class="product-list">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="product-card">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p>Price: $<?= $row['price'] ?></p>
        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
    <?php endwhile; ?>
</div>
</body>
</html>
