<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_panel.php");
    exit();
}

$id = intval($_POST['id']);
$product_name = $_POST['product_name'] ?? '';
$price = floatval($_POST['price'] ?? 0);
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';

$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle image upload if any
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'images/products/'; // Make sure this folder exists and is writable
    $tmpName = $_FILES['image']['tmp_name'];
    $fileName = basename($_FILES['image']['name']);
    $targetFile = $uploadDir . time() . "_" . $fileName;

    if (move_uploaded_file($tmpName, $targetFile)) {
        $imagePath = $targetFile;
    } else {
        die("Failed to upload image.");
    }
}

// Prepare SQL for update
if ($imagePath) {
    // Update all fields including image
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, category=?, image=? WHERE id=?");
    $stmt->bind_param("sdsssi", $product_name, $price, $description, $category, $imagePath, $id);
} else {
    // Update without changing image
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, category=? WHERE id=?");
    $stmt->bind_param("sdssi", $product_name, $price, $description, $category, $id);
}

if ($stmt->execute()) {
    header("Location: admin_panel.php?msg=Product updated successfully");
} else {
    echo "Error updating product: " . $conn->error;
}

$stmt->close();
$conn->close();
