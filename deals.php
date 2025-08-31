<?php
session_start();

// IMPORTANT: Include your database configuration file
// Ensure 'includes/db.php' exists and contains your database connection ($conn)
require_once 'includes/db.php'; 

// Handle add to cart (This block must come BEFORE any HTML output)
// The form action will now submit to deals.php, so this block will process it.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Sanitize product_id to ensure it's an integer
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    if ($product_id) {
        // Fetch product details directly from the database for security (prevents client-side manipulation)
        $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        // 'i' for integer type of product_id
        $stmt->bind_param("i", $product_id); 
        $stmt->execute();
        $result = $stmt->get_result();
        $productDetails = $result->fetch_assoc();
        $stmt->close();

        if ($productDetails) {
            $name = $productDetails['name'];
            $price = floatval($productDetails['price']); // Ensure price is a float

            // Initialize cart if it doesn't exist
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // If product is already in cart, increase quantity
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += 1;
            } else {
                // Add new product to cart with quantity 1
                $_SESSION['cart'][$product_id] = [
                    'id' => $product_id,
                    'name' => $name,
                    'price' => $price,
                    'quantity' => 1
                ];
            }
        } else {
            // Optional: Log or display an error if product ID not found
            error_log("Attempted to add non-existent product ID: " . $product_id);
        }
    } else {
        // Optional: Log or display an error if product_id is invalid
        error_log("Invalid product ID received for add to cart: " . $_POST['product_id']);
    }

    // Redirect to the same page to prevent form re-submission on refresh
    // This will also refresh the page and show the updated cart state if the cart is displayed on this page
    // IMPORTANT: If you want to go to a separate cart.php page, change "deals.php" to "cart.php" here.
    header("Location: deals.php"); 
    exit; // Always exit after a header redirect
}

// --- Start: Dynamic Product Fetching for Deals ---
$dealProducts = []; // Initialize an empty array to store products fetched from DB

// Define the category name for deals.
// !!! IMPORTANT: This MUST EXACTLY match the category name in your 'products' table for deal items.
$targetCategory = 'Deals'; // Change this if your deals category is named differently (e.g., 'Special Deals')

// Prepare SQL statement to fetch products from the 'Deals' category
// We now select 'original_price' which you must add to your 'products' table.
$sql = "SELECT id, name, price, original_price, description, image FROM products WHERE category = ? ORDER BY id DESC";

if ($stmt = $conn->prepare($sql)) {
    // Bind the category parameter (s = string)
    $stmt->bind_param("s", $targetCategory);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dealProducts[] = $row;
        }
    }
    $stmt->close();
} else {
    // Log the error for debugging purposes (check your Apache/PHP error logs)
    error_log("Failed to prepare statement for deals category: " . $conn->error);
    // Display a user-friendly error message on the page
    echo "<p>Error: Could not load deals. Please try again later.</p>";
    $dealProducts = []; // Ensure the array is empty to prevent display issues
}

// It's good practice to close the database connection when no more DB operations are needed
// Only close if the connection variable ($conn) exists and is a valid mysqli object
// Note: If you include a header/footer that also uses $conn, you might want to close it after all inclusions.
// For simplicity, closing it here for the main deals page logic.
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
// --- End: Dynamic Product Fetching ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Today's Deals - EasyShop</title>
    <link rel="stylesheet" href="deals.css" />
    <link rel="stylesheet" href="style.css?v=1"> <style>
        /* CSS for the product grid and cards - move to deals.css for better organization */
        .deal-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .deal-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex: 0 0 calc(25% - 20px); /* Approx. 4 items per row, adjust for responsiveness */
            box-sizing: border-box; /* Include padding and border in element's total width */
            display: flex; /* Flexbox for internal layout */
            flex-direction: column; /* Stack elements vertically */
            justify-content: space-between; /* Push button to bottom */
            min-width: 220px; /* Minimum width to prevent cards from becoming too narrow */
            max-width: 280px; /* Max width for larger screens */
        }

        .deal-card img {
            max-width: 100%;
            height: 150px; /* Consistent image height */
            object-fit: contain; /* Ensure image fits within bounds without cropping */
            margin-bottom: 10px;
        }

        .deal-card h3 {
            font-size: 1.2em;
            margin-bottom: 5px;
            color: #333;
        }

        .deal-card p {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        /* Styling for original price (strikethrough) */
        del {
            color: #888;
            margin-right: 5px;
        }

        /* Styling for the main deal price */
        .deal-card p strong {
            color: #e44d26; /* Distinct color for deal price (e.g., orange/red) */
            font-size: 1.1em;
            display: inline-block; /* Ensure it stays next to original price if present */
        }
        
        /* Styling for the discount percentage text */
        .discount {
            color: #d9534f; /* Red color for discount text */
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Styling for the countdown timer text */
        .timer {
            color: #5cb85c; /* Green color for timer */
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        
        /* Styling for the "Add to Cart" button */
        .deal-card form button {
            background-color: #ff9900; /* Blue button */
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: auto; /* Pushes button to the bottom, useful if content height varies */
            width: 100%; /* Make button full width of card */
        }

        .deal-card form button:hover {
            background-color: #fa9e13ff; /* Darker blue on hover */
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="deal-container">
    <h1>🔥 Today's Deals</h1>

    <div class="deal-grid">
        <?php if (!empty($dealProducts)): ?>
            <?php foreach ($dealProducts as $p): ?>
                <div class="deal-card">
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" />
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    
                    <?php 
                    $displayPrice = floatval($p['price']); // Current deal price
                    // Get original price, ensuring it's a float. Default to 0 if not set or invalid.
                    $originalPrice = isset($p['original_price']) ? floatval($p['original_price']) : 0; 

                    $discountPercentage = 0;
                    // Check if there's a valid original price higher than the display price
                    if ($originalPrice > $displayPrice && $originalPrice > 0) {
                        $discountPercentage = round((($originalPrice - $displayPrice) / $originalPrice) * 100);
                    ?>
                        <p><del>$<?= htmlspecialchars(number_format($originalPrice, 2)) ?></del> <strong>$<?= htmlspecialchars(number_format($displayPrice, 2)) ?></strong></p>
                        <p class="discount">Save <?= htmlspecialchars($discountPercentage) ?>%</p>
                    <?php } else { ?>
                        <p><strong>$<?= htmlspecialchars(number_format($displayPrice, 2)) ?></strong></p>
                    <?php } ?>
                    
                    <p class="timer">⏳ <span class="countdown" data-hours="3"></span></p>
                    
                    <form action="deals.php" method="POST"> 
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No exciting deals found yet! Please add some products to the 'Deals' category in your admin panel and set their original prices.</p>
        <?php endif; ?>
    </div>
</main>

<script src="deals.js"></script>
</body>
</html>