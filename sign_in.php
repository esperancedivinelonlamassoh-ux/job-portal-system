
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign In | JobPortal</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    body {
      background-color: #f9fafb;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: #fff;
      width: 400px;
      padding: 40px 35px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    .logo {
      font-size: 28px;
      color: #003A9B;
      font-weight: bold;
      margin-bottom: 20px;
    }

    h2 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #222;
    }
    p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    .btn {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
      background: #fff;
      font-size: 15px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 12px;
    }
    .btn:hover {
      background: #f3f3f3;
    }

    .btn img {
      width: 20px;
      height: 20px;
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 20px 0;
      color: #777;
      font-size: 14px;
    }
    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #ddd;
      margin: 0 10px;
    }

    form {
      text-align: left;
    }

    label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
      font-weight: 500;
    }

    input[type="email"], input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      margin-bottom: 15px;
      outline: none;
    }

    input[type="email"]:focus, input[type="password"]:focus {
      border-color: #003A9B;
      box-shadow: 0 0 0 2px rgba(0, 58, 155, 0.1);
    }

    .continue-btn {
      width: 100%;
      background: #003A9B;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 12px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .continue-btn:hover {
      background: #002B74;
    }

    .terms {
      font-size: 12px;
      color: #777;
      margin-top: 15px;
      line-height: 1.5;
    }
    .terms a {
      color: #003A9B;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="login-box">
  <div class="logo">JobPortal</div>

  <h2>Ready to take the next step?</h2>
  <p>Create an account or sign in.</p>

  <button class="btn">
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
    Continue with Google
  </button>

  <button class="btn">
    <img src="https://www.svgrepo.com/show/303128/apple-logo.svg" alt="Apple">
    Continue with Apple
  </button>

  <div class="divider">or</div>

  <form action="authenticate.php" method="post">
    <label>Email address <span style="color:red;">*</span></label>
    <input type="email" name="email" placeholder="Enter your email" required>

    <label>Password <span style="color:red;">*</span></label>
    <input type="password" name="password" placeholder="Enter your password" required>

    <button href ="apply.php" type="submit" class="continue-btn">Continue →</button>
  </form>

  <p class="terms">
    By clicking any of the ‘Continue’ options above, you understand and agree to our
    <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.
  </p>
</div>

</body>
</html>
