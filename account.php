<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EasyShop - Login/Sign Up</title>
  <link rel="icon" type="image/png" href="images/logo2.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="account.css">
</head>
<body>
  <div class="container">
    <h1 class="page-title">EasyShop</h1>
<div style="text-align: center; margin-bottom: 20px;">
  <a href="admin_login.php" style="color: #ff9900; font-weight: bold; text-decoration: none; border: 1px solid #ff9900; padding: 8px 14px; border-radius: 6px;">
    🛠️ Admin Login
  </a>
</div>

    <div class="form-card">
      <div class="toggle-buttons">
        <button id="loginToggle" class="active"></button>
        <button id="signupToggle"></button>
      </div>

      <!-- Login Form -->
      <form id="loginForm" class="form active-form" action="login.php" method="POST">
  <h2>Login</h2>
  <input type="text" name="email" placeholder="Email" required />
  <input type="password" name="password" placeholder="Password" required />
  <div class="extra-options">
    <label><input type="checkbox" /> Remember me</label>
  </div>
  <button type="submit" class="submit-btn">Log In</button>
  <p class="switch-text">Don't have an account? <span id="goToSignUp">Sign up</span></p>
</form>


      <!-- Sign Up Form -->
      <form id="signupForm" class="form" action="signup.php" method="POST">
  <h2>Sign Up</h2>
  <input type="text" name="first_name" placeholder="First Name" required />
  <input type="text" name="last_name" placeholder="Last Name" required />
  <input type="email" name="email" placeholder="Email" required />
  <input type="password" name="password" placeholder="Password" required />
  <input type="hidden" name="role" value="user" /> <!-- default role -->
  <input type="password" placeholder="Confirm Password" required />
  <label class="checkbox">
    <input type="checkbox" required /> I agree to the privacy policy
  </label>
  <button type="submit" class="submit-btn">Sign Up</button>
  <p class="switch-text">Already have an account? <span id="goToLogin">Login</span></p>
</form>


    </div>
   <div class="social-login">
  <p>Or sign in with</p>
  <div class="social-icons">
    <a href="#"><i class="fab fa-facebook-f"></i></a>
    <a href="#"><i class="fab fa-twitter"></i></a>
    <a href="#"><i class="fab fa-google"></i></a>
    <a href="#"><i class="fab fa-instagram"></i></a>
  </div>
</div>

</div>
  </div>

  <script src="script_1.js"></script>
</body>
</html>