<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - EasyShop</title>
  <link rel="icon" type="image/png" href="images/logo2.jpg">
  <link rel="stylesheet" href="style.css">
  <style>
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
    <h2>Add New Product</h2>
    <form action="insert_product.php" method="POST" enctype="multipart/form-data">
      <label>Product Name</label>
      <input type="text" name="product_name" required>

      <label>Price</label>
      <input type="number" name="price" step="0.01" required>

      <label>Description</label>
      <textarea name="description" required></textarea>

      <label>Category</label>
      <select name="category" required>
        <option value="fashion">Fashion</option>
        <option value="mobile">Mobile</option>
        <option value="beauty">Beauty</option>
        <option value="toys">Toys</option>
        <option value="gifts">Gifts</option>
        <option value="pet">Pet</option>
        <option value="latest">Latest</option>
        <option value="grooming">Grooming</option>
        <option value="deals">deals</option>
      </select>

      <label>Product Image</label>
      <input type="file" name="image" accept="image/*" required>

      <button type="submit">Add Product</button>
    </form>
  </div>
</div>

</body>
</html>
