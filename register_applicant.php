<<<<<<< HEAD
<?php
include("DB.php");
session_start();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $first = trim($_POST['first_name'] ?? '');
  $last = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if(!$first || !$last || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password)<8){
    $err = 'Invalid input or password too short (min 8).';
  } else {
    $pdo = DB::get();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO applicants (first_name,last_name,email,password_hash,phone,gender,dob) VALUES (?,?,?,?,?,?,?)');
    try {
      $stmt->execute([$first,$last,$email,$hash,$_POST['phone'] ?? null,$_POST['gender'] ?? null,$_POST['dob'] ?? null]);
      header('Location: login_applicant.php');
      exit;
    } catch(PDOException $e){
      $err = 'Email already used or DB error.';
    }
  }
}
?>
<?php

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jobportal.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>

<?php if(!empty($err)) echo '<p style="color:red">'.$err.'</p>'; ?>
<form method="post">
<h2>Applicant Registration</h2><br><br>
First name: <input name="first_name" required><br><br>
Last name: <input name="last_name" required><br><br>
Email: <input name="email" type="email" required><br><br>
Phone: <input name="phone"><br><br>
Gender: <select name="gender"><option value="">--</option><option>male</option><option>female</option><option>other</option></select><br><br>
DOB: <input name="dob" type="date"><br><br>
Password: <input name="password" type="password" required><br><br>
<button type="submit">Register</button><br><br>
<p> already have an account? <a href = "login_applicant.php">login here</a></p>
</form>
=======
<?php
include("DB.php");
session_start();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $first = trim($_POST['first_name'] ?? '');
  $last = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if(!$first || !$last || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password)<8){
    $err = 'Invalid input or password too short (min 8).';
  } else {
    $pdo = DB::get();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO applicants (first_name,last_name,email,password_hash,phone,gender,dob) VALUES (?,?,?,?,?,?,?)');
    try {
      $stmt->execute([$first,$last,$email,$hash,$_POST['phone'] ?? null,$_POST['gender'] ?? null,$_POST['dob'] ?? null]);
      header('Location: login_applicant.php');
      exit;
    } catch(PDOException $e){
      $err = 'Email already used or DB error.';
    }
  }
}
?>
<?php

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jobportal.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>

<?php if(!empty($err)) echo '<p style="color:red">'.$err.'</p>'; ?>
<form method="post">
<h2>Applicant Registration</h2><br><br>
First name: <input name="first_name" required><br><br>
Last name: <input name="last_name" required><br><br>
Email: <input name="email" type="email" required><br><br>
Phone: <input name="phone"><br><br>
Gender: <select name="gender"><option value="">--</option><option>male</option><option>female</option><option>other</option></select><br><br>
DOB: <input name="dob" type="date"><br><br>
Password: <input name="password" type="password" required><br><br>
<button type="submit">Register</button><br><br>
<p> already have an account? <a href = "login_applicant.php">login here</a></p>
</form>
>>>>>>> 76038d0 (commit changes)
<hr><footer>Job Portal - Dev</footer></body></html>