<?php
include("DB.php");
session_start();
$id = intval($_GET['id'] ?? 0);
$pdo = DB::get();
$stmt = $pdo->prepare('SELECT j.*, o.name as org_name, o.id as org_id FROM jobs j JOIN organizations o ON j.organization_id = o.id WHERE j.id = ? LIMIT 1');
$stmt->execute([$id]);
$job = $stmt->fetch();
if(!$job){ echo 'Job not found'; exit; }?>
<?php

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body><nav><a href="list_jobs.php">Jobs</a> | <?php if(is_logged_applicant()): ?><a href="dashboard_applicant.php">My Dashboard</a> | <a href="logout.php">Logout</a><?php endif; ?></nav><hr>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jobportal.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>
<h2><?php echo htmlspecialchars($job['title']);?></h2>
<p>Organization: <?php echo htmlspecialchars($job['org_name']);?></p>
<p>Location: <?php echo htmlspecialchars($job['location']);?></p>
<p><?php echo nl2br(htmlspecialchars($job['description']));?></p>
<?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'): ?>
  <a href="apply.php?job_id=<?php echo $job['id'];?>">Apply for this job</a>
<?php else: ?>
  <a href="login_applicant.php">Login as Applicant to Apply</a>
<?php endif; ?>
<hr><footer>Job Portal - Dev</footer></body></html>