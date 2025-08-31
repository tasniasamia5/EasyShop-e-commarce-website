<?php
session_start();
$response = [];

if (isset($_SESSION['first_name'])) {
    $response['logged_in'] = true;
    $response['first_name'] = $_SESSION['first_name'];
    $response['role'] = $_SESSION['role'];
} else {
    $response['logged_in'] = false;
}

echo json_encode($response);
?>
