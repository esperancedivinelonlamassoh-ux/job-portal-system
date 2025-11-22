
<?php
include("DB.php");
session_start();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['org_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if(!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password)<8){
    $err = 'Invalid input or password too short.';
  } else {
    $pdo = DB::get();
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare('INSERT INTO organizations (name,slug,description,location,website) VALUES (?,?,?,?,?)');
      $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $name));
      $stmt->execute([$name, $slug, $_POST['description'] ?? null, $_POST['location'] ?? null, $_POST['website'] ?? null]);
      $org_id = $pdo->lastInsertId();
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt2 = $pdo->prepare('INSERT INTO org_users (organization_id,name,email,password_hash,role) VALUES (?,?,?,?,?)');
      $stmt2->execute([$org_id, $_POST['contact_name'] ?? $name, $email, $hash, 'admin']);
      $pdo->commit();
      header('Location: login_org.php');
      exit;
    } catch(Exception $e){
      $pdo->rollBack();
      $err = 'Error creating organization or email used.';
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
    <link rel="stylesheet" href="css/registerorg.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>

<?php if(!empty($err)) echo '<p style="color:red">'.$err.'</p>'; ?>
<form method="post">
<h2>Organization Registration</h2><br><br>
Organization name: <input name="org_name" required><br><br>
Contact name: <input name="contact_name"><br><br>
Email: <input name="email" type="email" required><br><br>
Location: <input name="location"><br><br>
Website: <input name="website"><br><br>
Description:<br><textarea name="description"></textarea><br><br>
Password: <input name="password" type="password" required><br><br>
<button type="submit">Register Organization</button><br><br>
<p> already have an account? <a href=" login_org.php">login here</a></p>
</form>
