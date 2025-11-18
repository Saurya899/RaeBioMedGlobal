<?php
session_start();
include('./config/db.php');

$error = ""; // error message holder

if (isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = mysqli_prepare($conn, "SELECT id, email, password FROM admin WHERE email = ?");
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($result)) {
    if ($password === $row['password']) {
      $_SESSION['admin_id'] = $row['id'];
      $_SESSION['admin_email'] = $row['email'];
      header("Location: pages/dashboard.php");
      exit;
    } else {
      $error = "Invalid password. Please try again.";
    }
  } else {
    $error = "Wrong Email Id.";
  }

  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Control Panel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #4a4d52ff 0%, #92959eff 100%);
      padding: 20px;
    }

    .container {
      display: flex;
      max-width: 1000px;
      width: 100%;
      background: white;
      border-radius: 15px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
      overflow: hidden;
    }

    .left-panel {
      flex: 1;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .left-panel h1 {
      font-size: 2.8rem;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .left-panel p {
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 30px;
      max-width: 350px;
    }

    .right-panel {
      flex: 1;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }

    .logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo .fa-user-lock {
      font-size: 2.5rem;
      color: #1e3c72;
      margin-bottom: 10px;
    }

    .logo h2 {
      color: #333;
      font-size: 1.8rem;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group .fa-user,
    .fa-lock {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #1e3c72;
    }

    .input-group input {
      width: 100%;
      padding: 15px 45px;
      border: 2px solid #e1e1e1;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .input-group input:focus {
      border-color: #1e3c72;
      outline: none;
      box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
    }

    /* 👁️ Password Toggle Icon */
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #1e3c72;
      cursor: pointer;
    }

    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      font-size: 0.9rem;
    }

    .remember {
      display: flex;
      align-items: center;
    }

    .remember input {
      margin-right: 8px;
    }

    .forgot {
      color: #1e3c72;
      text-decoration: none;
      font-weight: 500;
    }

    .forgot:hover {
      text-decoration: underline;
    }

    .btn {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      border: none;
      padding: 15px;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
    }

    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(30, 60, 114, 0.4);
    }

    .btn:active {
      transform: translateY(-1px);
    }

    /* Alert Box */
    .alert {
      margin-bottom: 20px;
      padding: 15px 40px 15px 15px;
      border-radius: 8px;
      font-weight: 500;
      text-align: center;
      position: relative;
    }

    .alert.error {
      background-color: #f8d7da;
      color: #842029;
      border: 1px solid #f5c2c7;
    }

    .alert.success {
      background-color: #d1e7dd;
      color: #0f5132;
      border: 1px solid #badbcc;
    }

    /* ❌ Close Button */
    .close-btn {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      color: inherit;
      font-size: 1.2rem;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        max-width: 450px;
      }

      .left-panel {
        padding: 30px 20px;
      }

      .left-panel h1 {
        font-size: 2rem;
      }

      .right-panel {
        padding: 30px;
      }
    }

    @media (max-width: 480px) {
      .options {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .input-group input {
        padding: 12px 40px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="left-panel">
      <h1>Welcome Back!</h1>
      <p>Sign in to your account to access exclusive features and personalized content.</p>
    </div>

    <div class="right-panel">
      <div class="logo">
        <i class="fa-solid fa-user-lock"></i>
        <h2>Account Login</h2>
      </div>
      <form method="post">
        <?php if ($error != ""): ?>
          <div class="alert error" id="errorAlert">
            <?php echo $error; ?>
            <button type="button" class="close-btn" onclick="closeAlert()">&times;</button>
          </div>
        <?php endif; ?>

        <div class="input-group">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Username or Email" name="email" required>
        </div>

        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Password" name="password" id="password" required>
          <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>
        <!-- 
        <div class="options">
          <div class="remember">
            <input type="checkbox" id="remember">
            <label for="remember">Remember me</label>
          </div>
          <a href="#" class="forgot">Forgot Password?</a>
        </div> -->

        <button type="submit" class="btn" name="login">Login</button>
      </form>
    </div>
  </div>

  <script>
    //  Password Show/Hide
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", () => {
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      togglePassword.classList.toggle("fa-eye-slash");
    });

    // ❌ Close Alert
    function closeAlert() {
      const alertBox = document.getElementById("errorAlert");
      if (alertBox) {
        alertBox.style.opacity = "0";
        setTimeout(() => alertBox.style.display = "none", 300);
      }
    }
  </script>
</body>

</html>