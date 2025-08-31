<?php
$conn = new mysqli("localhost", "root", "", "easyshop");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM search_history ORDER BY search_time DESC");

echo "<h2>Search History</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['keyword']) . " - " . $row['search_time'] . "</li>";
}
echo "</ul><a href='index.html'>Back to Home</a>";

$conn->close();
?>