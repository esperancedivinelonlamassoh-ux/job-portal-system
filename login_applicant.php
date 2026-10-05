<?php
include("DB.php");
session_start();

$err = ''; 
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = DB::get();

    $stmt = $pdo->prepare('SELECT * FROM applicants WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $err = 'This account does not exist. Please register first.';
    } elseif (!password_verify($password, $user['password_hash'])) {
        $err = 'Invalid password. Try again.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_type'] = 'applicant';
        $_SESSION['applicant_id'] = $user['id'];
        $_SESSION['applicant_name'] = $user['full_name'] ?? '';

        header('Location: jobs.php');
        exit;
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Applicant Login - JobPortal CM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,#1e293b,#0f172a);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* CARD */
.container {
    background:white;
    padding:35px;
    border-radius:12px;
    width:350px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

/* TITLE */
h2 {
    text-align:center;
    color:#007a3d;
    margin-bottom:20px;
}

/* ERROR */
.error {
    background:#ffe6e6;
    color:#d8000c;
    padding:10px;
    border-radius:5px;
    margin-bottom:15px;
    text-align:center;
}

/* INPUT */
input {
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-top:5px;
}

input:focus {
    border-color:#007a3d;
    outline:none;
}

/* PASSWORD WRAPPER */
.password-box {
    position:relative;
}

.toggle {
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
}

/* BUTTON */
button {
    width:100%;
    padding:12px;
    background:#007a3d;
    color:white;
    border:none;
    border-radius:6px;
    margin-top:15px;
    font-weight:bold;
    cursor:pointer;
}

button:hover {
    background:#005e2e;
}

/* LINKS */
.links {
    display:flex;
    justify-content:space-between;
    margin-top:10px;
    font-size:13px;
}

.links a {
    color:#007a3d;
    text-decoration:none;
}

.links a:hover {
    text-decoration:underline;
}

/* FOOT TEXT */
p {
    text-align:center;
    margin-top:20px;
}
</style>
</head>

<body>

<div class="container">
    <h2>Applicant Login</h2>

    <?php if (!empty($err)): ?>
        <div class="error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post">

        <label>Email</label>
        <input name="email" type="email" required 
               value="<?= htmlspecialchars($email) ?>">

        <br><br>

        <label>Password</label>
        <div class="password-box">
            <input name="password" type="password" id="password" required>
            <span class="toggle" onclick="togglePassword()">👁️</span>
        </div>

        <!-- 🔥 FORGOT PASSWORD LINK -->
        <div class="links">
            <span></span>
            <a href="reset_password.php">Forgot Password?</a>
        </div>

        <button type="submit">Login</button>
    </form>

    <p>
        Don't have an account? 
        <a href="register_applicant.php">Create one</a>
    </p>
</div>

<script>
function togglePassword() {
    var input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>