<<<<<<< HEAD
<?php
include("DB.php");
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!=='org_user'){ header('Location: login_org.php'); exit; }
$job_id = intval($_GET['job_id'] ?? 0);
$pdo = DB::get();
$stmt = $pdo->prepare('SELECT a.*, ap.first_name, ap.last_name, ap.email FROM applications a JOIN applicants ap ON a.applicant_id = ap.id WHERE a.job_id = ?');
$stmt->execute([$job_id]);
$apps = $stmt->fetchAll();?>
<?php

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body><nav><a href="list_jobs.php">Jobs</a> | <?php if(is_logged_applicant()): ?><a href="dashboard_applicant.php">My Dashboard</a> | <a href="logout.php">Logout</a><?php else: ?><a href="register_applicant.php">Register Applicant</a> | <a href="login_applicant.php">Login Applicant</a><?php endif; ?> | <?php if(is_logged_org()): ?><a href="dashboard_org.php">Org Dashboard</a><?php else: ?><a href="register_org.php">Register Org</a> | <a href="login_org.php">Login Org</a><?php endif; ?></nav><hr>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jobportal.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>
<h2>Applications for Job #<?php echo $job_id;?></h2>
<?php foreach($apps as $app): ?>
  <div style="border:1px solid #ccc;padding:8px;margin:8px;">
    <p>Applicant: <?php echo htmlspecialchars($app['first_name'].' '.$app['last_name']);?> (<?php echo htmlspecialchars($app['email']);?>)</p>
    <p>Status: <?php echo htmlspecialchars($app['status']);?></p>
    <p><a href="view_application.php?id=<?php echo $app['id'];?>">View</a> | <a href="action_application.php?id=<?php echo $app['id'];?>&action=approve">Approve</a> | <a href="action_application.php?id=<?php echo $app['id'];?>&action=reject">Reject</a> | <a href="schedule_interview.php?app_id=<?php echo $app['id'];?>">Schedule Interview</a></p>
  </div>
<?php endforeach; ?>
=======
<?php
include("DB.php");
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!=='org_user'){ header('Location: login_org.php'); exit; }
$job_id = intval($_GET['job_id'] ?? 0);
$pdo = DB::get();
$stmt = $pdo->prepare('SELECT a.*, ap.first_name, ap.last_name, ap.email FROM applications a JOIN applicants ap ON a.applicant_id = ap.id WHERE a.job_id = ?');
$stmt->execute([$job_id]);
$apps = $stmt->fetchAll();?>
<?php

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body><nav><a href="list_jobs.php">Jobs</a> | <?php if(is_logged_applicant()): ?><a href="dashboard_applicant.php">My Dashboard</a> | <a href="logout.php">Logout</a><?php else: ?><a href="register_applicant.php">Register Applicant</a> | <a href="login_applicant.php">Login Applicant</a><?php endif; ?> | <?php if(is_logged_org()): ?><a href="dashboard_org.php">Org Dashboard</a><?php else: ?><a href="register_org.php">Register Org</a> | <a href="login_org.php">Login Org</a><?php endif; ?></nav><hr>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/jobportal.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>
<h2>Applications for Job #<?php echo $job_id;?></h2>
<?php foreach($apps as $app): ?>
  <div style="border:1px solid #ccc;padding:8px;margin:8px;">
    <p>Applicant: <?php echo htmlspecialchars($app['first_name'].' '.$app['last_name']);?> (<?php echo htmlspecialchars($app['email']);?>)</p>
    <p>Status: <?php echo htmlspecialchars($app['status']);?></p>
    <p><a href="view_application.php?id=<?php echo $app['id'];?>">View</a> | <a href="action_application.php?id=<?php echo $app['id'];?>&action=approve">Approve</a> | <a href="action_application.php?id=<?php echo $app['id'];?>&action=reject">Reject</a> | <a href="schedule_interview.php?app_id=<?php echo $app['id'];?>">Schedule Interview</a></p>
  </div>
<?php endforeach; ?>
>>>>>>> 76038d0 (commit changes)
<hr><footer>Job Portal - Dev</footer></body></html>