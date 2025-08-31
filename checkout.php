<?php
session_start();

if (!isset($_SESSION['user_email']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];
$cart = $_SESSION['cart'];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Handle payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_checkout'])) {
    $bkash_number = $_POST['bkash_number'];
    $trxid = $_POST['trxid'];

    if (strlen($bkash_number) === 11 && !empty($trxid)) {
        foreach ($cart as $item) {
            $name = $item['name'];
            $price = $item['price'];
            $quantity = $item['quantity'];
            $total = $price * $quantity;

            $stmt = $conn->prepare("INSERT INTO orders (user_email, product_name, price, quantity, total, bkash_number, trxid) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssdiiis", $user_email, $name, $price, $quantity, $total, $bkash_number, $trxid);
                $stmt->execute();
                $stmt->close();
            }
        }

        $_SESSION['cart'] = [];
        header("Location: checkout_success.php");
        exit();
    } else {
        $error_message = "Invalid bKash number or transaction ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>bKash Payment - EasyShop</title>
  <link rel="icon" type="image/png" href="images/logo2.jpg">
  <link rel="stylesheet" href="css/checkout.css">
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
    .container { max-width: 500px; margin: auto; background: white; border: 1px solid #ccc; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .checkout-btn { padding: 10px 20px; background: orange; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    .checkout-btn:hover { background: #ffd64f; }
    .error { color: red; margin-bottom: 10px; }
    .trx-field { display: flex; gap: 10px; align-items: center; }
    .trx-field input { flex: 1; }
  </style>
</head>
<body>

  <nav class="navbar">
    <a href="index.php"><strong>EasyShop</strong></a>
  </nav>

  <div class="container">
    <h2>📲 bKash Payment</h2>
    <p><strong>Total to Pay:</strong> $<?= number_format($subtotal, 2) ?></p>
    <p>Send the amount to this number: <strong>01XXXXXXXXX</strong></p>
    <p>Then click <strong>Generate TrxID</strong> and paste it below to simulate SMS confirmation.</p>

    <?php if (isset($error_message)): ?>
      <p class="error"><?= $error_message ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="bkash_number">Your bKash Number</label>
        <input type="text" name="bkash_number" id="bkash_number" required pattern="01[0-9]{9}" placeholder="e.g., 017xxxxxxxx">
      </div>

      <div class="form-group">
        <label for="trxid">Transaction ID (TrxID)</label>
        <div class="trx-field">
          <input type="text" name="trxid" id="trxid" required readonly>
          <button type="button" onclick="generateTrxID()">Generate TrxID</button>
        </div>
      </div>

      <input type="hidden" name="do_checkout" value="1">
      <button class="checkout-btn" type="submit">✅ Confirm & Place Order</button>
    </form>
  </div>

  <script>
    function generateTrxID() {
      const random = Math.random().toString(36).substr(2, 10).toUpperCase();
      const trxid = "TRX" + random.slice(0, 7);
      document.getElementById("trxid").value = trxid;
      alert("Your TrxID is: " + trxid);
    }
  </script>

</body>
</html>
