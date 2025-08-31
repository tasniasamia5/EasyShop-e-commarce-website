<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_name = $_POST['product_name'];
$price = $_POST['price'];
$description = $_POST['description'];
$category = $_POST['category'];

// Handle image upload
$target_dir = "product_images/";
$image_name = basename($_FILES["image"]["name"]);
$target_file = $target_dir . time() . "_" . $image_name;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $sql = "INSERT INTO products (name, price, description, category, image)
            VALUES ('$product_name', '$price', '$description', '$category', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        header("Location: {$category}.php"); // redirect to the respective category page
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error uploading image.";
}

$conn->close();
?>
