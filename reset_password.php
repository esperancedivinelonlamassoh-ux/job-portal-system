<?php
include("DB.php");

$pdo = DB::get();
$token = $_GET['token'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM applicants WHERE reset_token=?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $pdo->prepare("UPDATE applicants 
                   SET password_hash=?, reset_token=NULL 
                   WHERE id=?")
        ->execute([$new_password, $user['id']]);

    echo "✅ Password updated successfully! <a href='login_applicant.php'>Login</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 320px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        input[type="password"]:focus {
            border-color: #4facfe;
            box-shadow: 0 0 5px rgba(79,172,254,0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4facfe;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #00c6ff;
        }

        .note {
            font-size: 12px;
            color: gray;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Reset Password</h2>

    <form method="post">
        <input type="password" name="password" placeholder="Enter new password" required>
        <button type="submit">Update Password</button>
    </form>

    <div class="note">
        Choose a strong password for better security 🔒
    </div>
</div>

</body>
</html>