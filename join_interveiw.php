<?php
include("DB.php");
session_start();
$id = intval($_GET['interview_id'] ?? 0);
$pdo = DB::get();
$stmt = $pdo->prepare('SELECT i.*, a.applicant_id FROM interviews i JOIN applications a ON i.application_id = a.id WHERE i.id = ? LIMIT 1');
$stmt->execute([$id]);
$iv = $stmt->fetch();
if(!$iv) { echo 'Interview not found'; exit; }
if($iv['meeting_provider'] !== 'jitsi'){ echo 'Provider not supported in this simple demo'; exit; }
$room = htmlspecialchars(basename($iv['meeting_link']));
?><?php
session_start();
function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body><nav><a href="list_jobs.php">Jobs</a> | <?php if(is_logged_applicant()): ?><a href="dashboard_applicant.php">My Dashboard</a> | <a href="logout.php">Logout</a><?php else: ?><a href="register_applicant.php">Register Applicant</a> | <a href="login_applicant.php">Login Applicant</a><?php endif; ?> | <?php if(is_logged_org()): ?><a href="dashboard_org.php">Org Dashboard</a><?php else: ?><a href="register_org.php">Register Org</a> | <a href="login_org.php">Login Org</a><?php endif; ?></nav><hr>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jobportal.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>
<h2>Interview Room</h2>
<p>Joining room: <?php echo $room;?></p>
<iframe src="https://meet.jit.si/<?php echo $room;?>" style="width:100%;height:600px;border:0;"></iframe>
<hr><footer>Job Portal - Dev</footer></body></html>