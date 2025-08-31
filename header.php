<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="images/logo2.jpg">
<link rel="stylesheet" href="header.css">

<nav>
    <a href="index.php">
        <img src="images/logo2.jpg" width="100" alt="EasyShop Logo">
    </a>

    <div class="nav-country">
        <img src="images/location icon.png" height="20" alt="Location">
        <div>
            <p>Deliver to</p>
            <h1>Bangladesh</h1>
        </div>
    </div>

    <form action="search.php" method="GET" class="nav-search">
        <div class="nav-search-category">
            <p>All</p>
        </div>
        <input type="text" name="query" class="nav-search-input" placeholder="Search EasyShop">
        <button type="submit" style="background: none; border: none;">
            <img src="images/Search icon.png" class="nav-search-icon" alt="Search">
        </button>
    </form>

    <div class="nav-language">
        <img src="images/US Flag icon.png" width="25px" alt="Flag">
        <p>EN</p>
    </div>

    <?php if (isset($_SESSION['user_email'])): ?>
        <div class="nav-user">
            <img src="images/user-icon.png" alt="User" width="30px">
            <a href="my_account.php" class="nav-text">
                <h1><?php echo $_SESSION['first_name']; ?></h1>
            </a>
        </div>
    <?php else: ?>
        <div class="nav-user">
            <img src="images/user.png" alt="User" width="30px">
            <a href="account.php" class="nav-text">
                <h1>Sign in</h1>
            </a>
        </div>
    <?php endif; ?>

    <a href="cart.php" class="nav-cart">
        <img src="images/Cart icon.png" width="35px" alt="Cart">
        <h4>Cart</h4>
    </a>
</nav>

<div class="nav-bottom">
    <div id="all-toggle">
        <img src="images/menu.png" width="25px" alt="Menu">
        <p>All</p>
    </div>
    <a href="deals.php"><p>Today's Deal</p></a>
<a href="#about"><p>About Us</p></a>
    <?php if ($is_admin): ?>
        <a href="admin_panel_redirect.php"><p>Admin Panel</p></a>
    <?php endif; ?>
    <a href="order_history.php"><p>History</p></a>
</div>

<div id="all-categories" class="all-categories hidden">
    <a href="index.php">Home</a>
    <a href="toys.php">Toys</a>
    <a href="mobile.php">Mobiles</a>
    <a href="beauty.php">Beauty</a>
    <a href="gifts.php">Gifts</a>
    <a href="pet.php">Pet Food</a>
    <a href="fashion.php">Fashion</a>
    <a href="latest.php">Latest Devices</a>
    <a href="grooming.php">Grooming</a>
</div>

<script src="header.js"></script>
