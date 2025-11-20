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

    // Check if applicant exists
    $stmt = $pdo->prepare('SELECT * FROM applicants WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $err = 'You don’t exist in our system. Please register first.';
    } elseif (!password_verify($password, $user['password_hash'])) {
        $err = 'Invalid password. Please try again.';
    } else {
        // Successful login
        session_regenerate_id(true);
        $_SESSION['user_type'] = 'applicant';
        $_SESSION['applicant_id'] = $user['id'];
        $_SESSION['applicant_name'] = $user['full_name'] ?? '';

        header('Location: jobs.php');
        exit;
    }
}

function is_logged_applicant() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'applicant';
}
function is_logged_org() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'org_user';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Indeed - Applicant Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px 40px;
            width: 350px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        h2 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 10px;
        }

        .error {
            background-color: #ffe6e6;
            color: #d8000c;
            border: 1px solid #d8000c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #d0d7ff;
            border-radius: 5px;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #2b5cff;
        }

        .continue-btn {
            width: 100%;
            padding: 12px;
            background-color: #2b5cff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        .continue-btn:hover {
            background-color: #244dcc;
        }

        p {
            text-align: center;
            color: #555;
            font-size: 14px;
            margin-top: 20px;
        }

        p a {
            color: #2b5cff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
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
            <label for="email">Email address *</label><br>
            <input name="email" id="email" type="email" required 
                   value="<?= htmlspecialchars($email) ?>"><br><br>

            <label for="password">Password *</label><br>
            <input name="password" id="password" type="password" required
                   value="<?= htmlspecialchars($password) ?>">

            <button type="submit" class="continue-btn">Continue →</button><br><br>
        </form>

        <p>Don’t have an account? <a href="register_applicant.php">Register here</a></p>
    </div>
</body>
</html>
