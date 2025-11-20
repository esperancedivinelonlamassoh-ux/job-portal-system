<?php
session_start();
include 'DB.php';

// Assume the user is logged in
$applicant_id = $_SESSION['applicant_id'] ?? 1;

// Fetch jobs the user has applied to
$applied_query = "SELECT j.*, a.status FROM jobs j 
                  JOIN applications a ON j.id = a.job_id 
                  WHERE a.applicant_id = $applicant_id 
                  ORDER BY a.id DESC";
$applied_result = mysqli_query($conn, $applied_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Applied Jobs 🇨🇲 - JobConnect CM</title>
<style>
/* ===== BODY ===== */
body { 
    font-family: 'Segoe UI', Arial, sans-serif; 
    margin:0; 
    background:#f4f6f9; 
}

/* ===== HEADER ===== */
header { 
    background: linear-gradient(90deg, #007b3e, #d90909, #fcd116); /* Cameroon flag colors */
    color:white; 
    padding:15px 0; 
    text-align:center; 
}
header h1 {
    margin:0; 
    font-size:28px;
    font-weight:bold;
}
nav { margin-top:5px; }
nav a { 
    color:white; 
    text-decoration:none; 
    margin:0 12px; 
    font-weight:bold;
    transition:0.3s;
}
nav a:hover, nav a.active { 
    color:#fff700; 
    text-decoration:underline; 
}

/* ===== CONTAINER ===== */
.container { 
    width:90%; 
    max-width:1100px; 
    margin:30px auto; 
}

/* ===== SECTION TITLE ===== */
.section-title { 
    color:#004aad; 
    font-size:24px; 
    margin-bottom:20px; 
    text-align:center;
}

/* ===== JOB CARDS ===== */
.job-card { 
    background:#fff; 
    padding:20px; 
    margin-bottom:20px; 
    border-radius:12px; 
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}
.job-card:hover { transform: translateY(-3px); }

.job-card h3 { 
    margin:0 0 8px; 
    color:#004aad;
}

.job-card .meta { 
    font-size:14px; 
    color:#555; 
}

.job-card .salary { 
    color:#007b3e; 
    font-weight:bold; 
    margin-top:5px;
}

.job-card .status { 
    font-weight:bold; 
    margin-top:8px; 
}

.status-Pending { color: #ff9800; }    /* Orange */
.status-Accepted { color: #28a745; }   /* Green */
.status-Rejected { color: #dc3545; }   /* Red */

/* ===== RESPONSIVE ===== */
@media(max-width:600px){
    nav a { display:block; margin:5px 0; }
    .container { width:95%; }
}
</style>
</head>
<body>

<header>
  <h1>Cameroon JobPortal</h1>
  <nav>
      <a href="home.php">Home </a>
      <a href="jobs.php">All Jobs </a>
      <a href="recommended_jobs.php">Recommended jobs</a>
      <a href="applied_jobs.php" class="active">Applied Jobs </a>
      <a href="about.php">About </a>
      <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
    <h2 class="section-title">Your Applied Jobs 🇨🇲 / Vos Postes Postulés</h2>

    <?php if(mysqli_num_rows($applied_result) > 0): ?>
        <?php while($job = mysqli_fetch_assoc($applied_result)): ?>
            <?php $status_class = 'status-'.htmlspecialchars($job['status']); ?>
            <div class="job-card">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <div class="meta"><?php echo htmlspecialchars($job['company_name']); ?> — <?php echo htmlspecialchars($job['location']); ?></div>
                <div class="meta">Category: <?php echo htmlspecialchars($job['category'] ?? 'Not specified / Non spécifié'); ?></div>
                <div class="salary">Salary / Salaire: <?php echo htmlspecialchars($job['salary'] ?? 'Negotiable / À négocier'); ?></div>
                <div class="status <?php echo $status_class; ?>">Application Status / Statut: <?php echo htmlspecialchars($job['status'] ?? 'Pending'); ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:#555;">You have not applied to any jobs yet. / Vous n’avez postulé à aucun emploi pour le moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
