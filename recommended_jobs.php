<?php
session_start();
include 'DB.php';

// Assume user is logged in
$user_id = $_SESSION['user_id'] ?? 1;

// Example logic: recommend jobs based on IT category (could be improved later)
$recommended_query = "SELECT * FROM jobs WHERE category='IT' ORDER BY date_posted DESC";
$recommended_result = mysqli_query($conn, $recommended_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recommended Jobs in Cameroon - JobConnect.cm</title>
<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        margin: 0;
        background: #f2f5f7;
        color: #333;
    }

    /* 🇨🇲 HEADER */
    header {
        background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
        color: white;
        padding: 20px 0;
        text-align: center;
    }

    header h1 {
        margin: 0;
        font-size: 26px;
        letter-spacing: 1px;
    }

    nav {
        margin-top: 8px;
    }

    nav a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    nav a:hover {
        text-decoration: underline;
    }

    /* MAIN CONTAINER */
    .container {
        width: 90%;
        margin: 30px auto;
        max-width: 1000px;
    }

    .section-title {
        color: #007a3d;
        margin-bottom: 20px;
        border-left: 5px solid #ce1126;
        padding-left: 10px;
        font-size: 22px;
    }

    /* JOB CARD */
    .job-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .job-card h3 {
        color: #004aad;
        margin: 0 0 8px;
        font-size: 20px;
    }

    .meta {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
    }

    .salary {
        color: #007a3d;
        font-weight: bold;
    }

    .apply-btn {
        background: #004aad;
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 10px;
        transition: background 0.3s ease;
    }

    .apply-btn:hover {
        background: #003580;
    }

    /* FOOTER 🇨🇲 */
    footer {
        background: #004aad;
        color: white;
        text-align: center;
        padding: 15px 0;
        margin-top: 40px;
        font-size: 14px;
    }
</style>
</head>
<body>

<header>
  <h1>Cameroon JobPortal</h1>
  <nav>
      <a href="home.php">Home</a>
      <a href="jobs.php">All Jobs</a>
      <a href="recommended_jobs.php">Recommended Jobs</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
    <h2 class="section-title">Recommended Jobs in Cameroon</h2>

    <?php if(mysqli_num_rows($recommended_result) > 0): ?>
        <?php while($job = mysqli_fetch_assoc($recommended_result)): ?>
            <div class="job-card">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <div class="meta"><strong>Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></div>
                <div class="meta"><strong>Location:</strong> <?php echo htmlspecialchars($job['location'] ?: 'Yaoundé'); ?></div>
                <div class="meta"><strong>Category:</strong> <?php echo htmlspecialchars($job['category'] ?? 'Not specified'); ?></div>
                <div class="salary"><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary'] ?? 'Negotiable'); ?> FCFA</div>
                <div class="meta"><strong>Posted on:</strong> <?php echo htmlspecialchars($job['date_posted'] ?? 'N/A'); ?></div>
                <a class="apply-btn" href="apply.php?id=<?php echo $job['id']; ?>">Apply Now</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No recommended jobs at the moment. Check back soon for new offers in Douala, Yaoundé, Bamenda, and beyond.</p>
    <?php endif; ?>
</div>

<footer>
    <p>© <?php echo date('Y'); ?> JobConnect.cm — Connecting talent across Cameroon 🇨🇲</p>
</footer>

</body>
</html>
