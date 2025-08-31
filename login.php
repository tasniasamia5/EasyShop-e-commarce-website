<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

// Create DB connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email and password from form
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL statement
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Login successful - set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['user_email'] = $email;

        header("Location: index.php");
        exit();
    } else {
        // Incorrect password
        echo "Incorrect password.";
    }
} else {
    // No user found
    echo "User not found.";
}

$conn->close();
?>
