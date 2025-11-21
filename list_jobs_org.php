<?php
include("DB.php");

session_start();
$pdo = DB::get();
$stmt = $pdo->query('SELECT j.*, o.name as org_name FROM jobs j JOIN organizations o ON j.organization_id = o.id WHERE j.is_active = 1 ORDER BY j.posted_at DESC LIMIT 50');
$jobs = $stmt->fetchAll();?>
<?php

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body><nav><a href="list_jobs.php">Jobs</a> | <?php if(is_logged_applicant()): ?><a href="dashboard_applicant.php">My Dashboard</a> | <a href="logout.php">Logout</a><?php else: ?><a href="register_applicant.php">Register Applicant</a> | <a href="login_applicant.php">Login Applicant</a><?php endif; ?> | <?php if(is_logged_org()): ?><a href="dashboard_org.php">Org Dashboard</a><?php else: ?><a href="register_org.php">Register Org</a> | <a href="login_org.php">Login Org</a><?php endif; ?></nav><hr>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/searchbar.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>
    <!--search bar-->
    <div class="container mt-4">
        <form method = "GET" action="list_jobs.php" class="d-flex justify-content-center">
            <input class="form-control me-2" type="search" name="search" placeholder="search for jobs..." value="<?php echo isset($_GET['search'])? htmlspecialchars($_GET['search']):'';?>"
            style="max-width: 400px;">
            <button class="button button-primary" type="submit">search</button>
</form>
</div>
<h2>Jobs</h2><br><br>
<?php foreach($jobs as $job): ?>
  <div style="border:1px solid #ccc;padding:8px;margin:8px;" class= "job-list">
    <h3><a href="job.php?id=<?php echo $job['id'];?>"><?php echo htmlspecialchars($job['title']);?></a></h3>
    <p><?php echo "<div class = 'org_name'> Organisation Name: ".htmlspecialchars($job['org_name'])."</div>";?> — <?php echo "<div class = 'location'> Location: ". htmlspecialchars($job['location'])."</div>";?></p>
    <p><?php echo "<div class = 'description'>Description: " .substr(htmlspecialchars($job['description']),0,200). "</div>";?>...</p>
  </div>
<?php endforeach; ?>
<hr><footer>Job Portal - Dev</footer></body></html>