<?php
session_start();
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyShop</title>
    <link rel="icon" type="image/png" href="images/logo2.jpg">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="/">
           <img src="images/logo2.jpg" width="100" alt="">
        </a>
        <div class="nav-country">
            <img src="images/location icon.png" height="20" alt="">
            <div>
                <p>Deliver to</p>
                <h1>Bangladesh</h1>
            </div>
        </div>
    <form action="search.php" method="GET" class="nav-search">
        <!-----<div class="nav-search">----->
            <div class="nav-search-category">
                <p>All</p>
            </div>
            <input type="text" name="query" class="nav-search-input" placeholder="Search EasyShop">
             <button type="submit" style="background: none; border: none;">
            <img src="images/Search icon.png" class="nav-search-icon"  alt="">
        </button>
    </form>
        <div class="nav-language">
           <img src="images/US Flag icon.png" width="25px" alt="">
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

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.querySelector('.dropdown');
        if (dropdown) {
            dropdown.addEventListener('mouseover', () => {
                dropdown.querySelector('.dropdown-content').style.display = 'block';
            });
            dropdown.addEventListener('mouseout', () => {
                dropdown.querySelector('.dropdown-content').style.display = 'none';
            });
        }
    });
</script>


        <a href="cart.php" class="nav-cart">
            <img src="images/shopping-cart.png" width="35px" alt="">
        </a>
    </nav>
<div class="nav-bottom">
        
    <div>
        <img src="images/menu.png" width="25px" alt="">
        <p>All</p>
        </div>

        <a href="deals.html"><p>Today's Deal</p></a>
        <p>About Us</p>
        <?php if ($is_admin): ?>
        <a href="admin_panel_redirect.php"><p>Admin pannal</p></a>
        <?php endif; ?>
        <a href="order_history.php"><p>History</p></a>
    </div>

    
    <div class="header-slider">
        <a href="#" class="control_prev">&#10094</a>
        <a href="#" class="control_next">&#10095</a>
         <ul>
            <li class="slider-item"><img src="images/beauty1-slider.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/gadgets.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/book_real.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/Kitchen slider.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/toy_banner.jpg" class="header-img" alt=""></li>
         </ul>
    </div>

    <div class="box-row header-box"  >
        <div class="box-col">
            <h3>Toys under $25</h3>
            <img src="images/box1-3.jpg" alt="">
            <a href="toys.php">Shop More</a>
       </div>
       <div class="box-col">
            <h3>Digital Moblie Phones</h3>
            <img src="images/box2-2.jpg" alt="">
            <a href="mobile.php">Shop More</a>
       </div>
       <div class="box-col">
           <h3>Beauty Essentials</h3>
           <img src="images/box2-1.jpg" alt="">
           <a href="beauty.php">Shop More</a>
      </div>
      <div class="box-col">
          <h3>Gifts</h3>
          <img src="images/box1-2.jpg" alt="">
          <a href="gifts.php">Shop More</a>
      </div>
</div>

<div class="box-row "  >
    <div class="box-col">
        <h3>Pets Food</h3>
        <img src="images/pet+food.jpg" alt="">
        <a href="pet.php">Shop More</a>
   </div>
   <div class="box-col">
        <h3>Fashion Mart</h3>
        <img src="images/Fashion Mart.jpg" alt="">
        <a href="fashion.php">Shop More</a>
   </div>
   <div class="box-col">
       <h3>Latest Devices</h3>
       <img src="images/Latest Device.jpg" alt="">
       <a href="latest.php">Shop More</a>
  </div>
  <div class="box-col">
    <h3>Grooming Products</h3>
    <img src="images/Grooming products.jpg" alt="">
    <a href="grooming.php">Shop More</a>
  </div>
</div>
<div class="product-slider">
    <div class="product-slider">
    <h2>New Trend</h2>

    <?php
    $conn = new mysqli("localhost", "root", "", "easyshop");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sample structure: orders -> order_items (product_id, quantity)
    // You can adjust table names/columns if different.
    $sql = "
        SELECT p.image, p.name 
        FROM products p 
        JOIN (
            SELECT product_id, SUM(quantity) AS total_sold 
            FROM order_items 
            GROUP BY product_id 
            ORDER BY total_sold DESC 
            LIMIT 6
        ) top_sellers ON p.id = top_sellers.product_id;
    ";

    $result = $conn->query($sql);
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
    <?php 
        endwhile;
    else:
        echo "<p>No trending products found.</p>";
    endif;

    $conn->close();
    ?>
</div>

</div>
<footer class="footer-section" id="contact">
    <div class="footer-content">
      <h4>Contact Us</h4>
      <p>Email: <a href="mailto:tasniasaima6@gmail.com">tasniasaima6@gmail.com</a></p>
      <p>Phone: +880 18420-94272</p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </footer>
<script src="script.js"></script>

</body>
</html>
