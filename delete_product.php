<?php
// delete_product.php
session_start();

if (!isset($_GET['id'])) {
    die('Product ID is required');
}

$id = intval($_GET['id']);

$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM products WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: admin_panel.php?msg=Product deleted successfully");
    exit();
} else {
    echo "Error deleting product: " . $conn->error;
}

$conn->close();
?>
