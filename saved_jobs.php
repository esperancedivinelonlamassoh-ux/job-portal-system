<?php
session_start();
include 'DB.php';

// Assume the user is logged in
$user_id = $_SESSION['user_id'] ?? 1;

// Fetch saved jobs
$saved_query = "SELECT j.* FROM jobs j 
                JOIN saved_jobs s ON j.id = s.job_id 
                WHERE s.user_id = $user_id 
                ORDER BY s.id DESC";
$saved_result = mysqli_query($conn, $saved_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saved Jobs - Job Portal</title>
<style>
body { font-family: Arial, sans-serif; margin:0; background:#f6f8fa; }
header { background:#004aad; color:white; padding:15px 0; text-align:center; }
nav a { color:white; text-decoration:none; margin:0 15px; font-weight:bold; }
.container { width:90%; margin:20px auto; }
.job-card { background:#fff; padding:15px; margin-bottom:15px; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,0.1);}
.job-card h3 { margin:0 0 5px; color:#004aad;}
.job-card .meta { font-size:14px; color:#555;}
.job-card .salary { color:green; font-weight:bold;}
.job-card .apply-btn { background:#004aad; color:white; padding:8px 12px; text-decoration:none; border-radius:5px; margin-top:10px; display:inline-block;}
.job-card .apply-btn:hover { background:#003580; }
.section-title { color:#004aad; margin-top:30px; margin-bottom:10px; }
</style>
</head>
<body>

<header>
  <h1>Job Portal</h1>
  <nav>
      <a href="home.php">Home</a>
      <a href="jobs.php">All Jobs</a>
      <a href="recommended_jobs.php">Recommended Jobs</a>
      <a href="applied_jobs.php">Applied Jobs</a>
      <a href="saved_jobs.php">Saved Jobs</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
    <h2 class="section-title">Your Saved Jobs</h2>

    <?php if(mysqli_num_rows($saved_result) > 0): ?>
        <?php while($job = mysqli_fetch_assoc($saved_result)): ?>
            <div class="job-card">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <div class="meta"><?php echo htmlspecialchars($job['company_name']); ?> — <?php echo htmlspecialchars($job['location']); ?></div>
                <div class="meta">Category: <?php echo htmlspecialchars($job['category'] ?? 'Not specified'); ?></div>
                <div class="salary">Salary: <?php echo htmlspecialchars($job['salary'] ?? 'Negotiable'); ?></div>
                <a class="apply-btn" href="apply.php?id=<?php echo $job['id']; ?>">Apply Now</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have not saved any jobs yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
