<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easyshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query !== '') {
    // Store search in history
    $stmt = $conn->prepare("INSERT INTO search_history (keyword) VALUES (?)");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $stmt->close();

    // Search products by name or category
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ?");
    $like = "%$query%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Search Results</title>
        <link rel="icon" type="image/png" href="images/logo2.jpg">
        <link rel="stylesheet" href="style.css?v=2">
        <style>
            .box-col {
                width: 22%;
                display: inline-block;
                margin: 1%;
                padding: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                vertical-align: top;
                text-align: center;
            }
            .box-col img {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }
        </style>
    </head>
    <body>
        <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<div class='box-row'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='box-col'>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt=''>";
                echo "<p>Price: $" . $row['price'] . "</p>";
                echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No products available for this search.</p>";
        }
        ?>
        <br><a href="index.php">Back to Home</a>
    </body>
    </html>
    <?php

    $stmt->close();
}
$conn->close();
?>
