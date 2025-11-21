<?php
include("DB.php");


session_start();
$pdo = DB::get();
$stmt = $pdo->query('SELECT j.*, o.name as org_name FROM jobs j JOIN organizations o ON j.organization_id = o.id WHERE j.is_active = 1 ORDER BY j.posted_at DESC LIMIT 50');
$jobs = $stmt->fetchAll();?>
<?php
$search= "";
if(isset($_GET['search'])){
    $search=$_GET['search'];
    $sql="SELECT * FROM products WHERE productID LIKE'%$search%' OR category LIKE '%category'";
}else{
    $sql= "SELECT * FROM products";
}
$result= $stmt->fetch();

function is_logged_applicant(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='applicant'; }
function is_logged_org(){ return isset($_SESSION['user_type']) && $_SESSION['user_type']==='org_user'; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Job Portal</title></head><body ><nav><a href="list_jobs.php">Jobs</a> | <?php if(is_logged_applicant()): ?><a href="dashboard_applicant.php">My Dashboard</a> | <a href="logout.php">Logout</a><?php else: ?><a href="register_applicant.php">Register Applicant</a> | <a href="login_applicant.php">Login Applicant</a><?php endif; ?> | <?php if(is_logged_org()): ?><a href="dashboard_org.php">Org Dashboard</a><?php else: ?><a href="register_org.php">Register Org</a> | <a href="login_org.php">Login Org</a><?php endif; ?></nav><hr> 
  <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/searchbar.css">
    <script scr=" js/bootstrap.bundle.min.js"></script>
    <!--search bar-->
    <div class= "search-bar" action= "list_jobs.php">
        <form method="GET" >
            <input type="text" name="search" placeholder="search for name or category" value="<?php echo htmlspecialchars($search)?>">
            <button type="submit">search</button>
           
    </form>
    </div>


<h2>Jobs</h2><br><br>
<?php foreach($jobs as $job): ?>
  
  
    <div class="job-card">
    <?php echo"<img src='" . htmlspecialchars($job['image']). "'>" ;?>
    <h3><a href="job.php?id=<?php echo $job['id'];?>"><?php echo htmlspecialchars($job['title']);?></a></h3>
    <p><?php echo "<div class = 'org_name'> Organisation Name: ".htmlspecialchars($job['org_name'])."</div>";?> — <?php echo "<div class = 'location'> Location: ". htmlspecialchars($job['location'])."</div>";?></p>
    <p><?php echo "<div class = 'description'>Description: " .substr(htmlspecialchars($job['description']),0,200). "</div>";?>...</p>
  </div>

<?php endforeach; ?>

<hr><footer>Job Portal - Dev</footer></body></html>