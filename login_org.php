<?php
include("DB.php");
session_start();

$email = '';
$password = '';
$err = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = DB::get();
    $stmt = $pdo->prepare('SELECT ou.*, o.id as org_id 
                           FROM org_users ou 
                           JOIN organizations o ON ou.organization_id = o.id 
                           WHERE ou.email = ? 
                           LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password_hash'])){
        session_regenerate_id(true);
        $_SESSION['user_type'] = 'org_user';
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['org_id'] = $user['org_id'];
        header('Location: admin.php');
        exit;
    } else {
        $err = 'Invalid credentials. Please try again.';
    }
}

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Portal</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jobportal.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php if(!empty($err)): ?>
    <p style="color:red; font-weight:bold;"><?php echo $err; ?></p>
<?php endif; ?>

<form method="post">
    <h2>Organization Login</h2><br><br>

    Email:  
    <input name="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>
    <br><br>

    Password:  
    <input name="password" type="password" value="<?php echo htmlspecialchars($password); ?>" required>
    <br><br>

    <button type="submit">Login</button><br><br>

    <p>Don't have an account? <a href="register_org.php">Register here</a></p>
</form>

<hr>
<footer>Job Portal - Dev</footer>

</body>
</html>
