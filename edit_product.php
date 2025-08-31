<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Product ID is required");
}

$id = intval($_GET['id']);

$conn = new mysqli("localhost", "root", "", "easyshop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    die("Product not found");
}

$product = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product - EasyShop</title>
  <link rel="icon" type="image/png" href="images/logo2.jpg">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #f4f4f4;
    }

    .form-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 90vh;
      background: #f8f8f8;
    }

    .form-box {
      background: white;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
    }

    .form-box h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 24px;
      color: #333;
    }

    .form-box label {
      display: block;
      margin-bottom: 5px;
      color: #333;
      font-weight: 500;
    }

    .form-box input,
    .form-box select,
    .form-box textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
      outline: none;
    }

    .form-box textarea {
      resize: vertical;
      min-height: 80px;
    }

    .form-box img {
      width: 100px;
      margin-bottom: 10px;
      border-radius: 8px;
    }

    .form-box button {
      background-color: #ff9900;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-weight: bold;
      width: 100%;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .form-box button:hover {
      background-color: #e68a00;
    }
  </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="form-wrapper">
  <div class="form-box">
    <h2>Edit Product</h2>
    <form action="update_product.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

      <label>Product Name</label>
      <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

      <label>Price</label>
      <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

      <label>Description</label>
      <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

      <label>Category</label>
      <select name="category" required>
        <?php
        $categories = ['fashion', 'mobile', 'beauty', 'toys', 'gifts', 'pet', 'latest', 'grooming'];
        foreach ($categories as $cat) {
            $selected = ($product['category'] === $cat) ? 'selected' : '';
            echo "<option value=\"$cat\" $selected>" . ucfirst($cat) . "</option>";
        }
        ?>
      </select>

      <label>Current Image</label><br>
      <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image"><br>

      <label>Change Image (optional)</label>
      <input type="file" name="image" accept="image/*">

      <button type="submit">Update Product</button>
    </form>
  </div>
</div>

</body>
</html>