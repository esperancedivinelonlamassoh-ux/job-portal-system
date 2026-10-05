<?php
include("DB.php");
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

/* JOBS */
$query = "SELECT * FROM jobs WHERE organization_id = '$org_id' ORDER BY date_posted DESC";
$result = mysqli_query($conn, $query);

/* STATS */
$total_jobs = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - JobPortal Cameroon 🇨🇲</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    margin: 0;
    display: flex;
    background: #f4f6f9;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    background: linear-gradient(180deg, #007a3d, #ce1126, #fcd116);
    color: white;
    padding: 20px;
    height: 100vh;
    position: fixed;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 8px;
    transition: 0.3s;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateX(5px);
}

/* MAIN */
.main {
    margin-left: 260px;
    padding: 30px;
    width: 100%;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    color: #007a3d;
    margin: 0;
}

/* STATS CARDS */
.stats {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.card {
    flex: 1;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    text-align: center;
}

.card h3 {
    margin: 0;
    color: #007a3d;
}

.card p {
    font-size: 22px;
    font-weight: bold;
}

/* BUTTON */
.post-btn {
    display: inline-block;
    background: #007a3d;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    margin-bottom: 20px;
    transition: 0.3s;
}

.post-btn:hover {
    background: #005e2e;
}

/* JOB CARDS */
.jobs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.job-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.job-card:hover {
    transform: translateY(-5px);
}

.job-title {
    font-size: 18px;
    font-weight: bold;
    color: #007a3d;
    margin-bottom: 10px;
}

.job-info {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
}

/* ACTION BUTTONS */
.actions {
    margin-top: 15px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    font-size: 13px;
}

.view { background: #17a2b8; }
.edit { background: #28a745; }
.delete { background: #dc3545; }

.btn:hover {
    opacity: 0.85;
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>Cameroon JobPortal</h2>
    <a href="admin.php">🏠 Dashboard</a>
    <a href="adminmessage.php">📩 Messages</a>
    <a href="post_job.php">📝 Post New Job</a>
    <a href="view_interview.php">📅 Interviews</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">

    <div class="header">
        <h1>Your Job Dashboard</h1>
        <a href="post_job.php" class="post-btn">+ Post Job</a>
    </div>

    <!-- STATS -->
    <div class="stats">
        <div class="card">
            <h3>Total Jobs</h3>
            <p><?= $total_jobs ?></p>
        </div>

        <div class="card">
            <h3>Active Jobs</h3>
            <p><?= $total_jobs ?></p>
        </div>

        <div class="card">
            <h3>Applications</h3>
            <p>--</p>
        </div>
    </div>

    <!-- JOB CARDS -->
    <div class="jobs">

        <?php
        if (mysqli_num_rows($result) > 0) {
            mysqli_data_seek($result, 0); // reset pointer

            while ($job = mysqli_fetch_assoc($result)) {

                $img = !empty($job['image']) ? $job['image'] : "default.jpg";

                echo "
                <div class='job-card'>

                    <div class='job-title'>".$job['title']."</div>

                    <div class='job-info'>📍 ".$job['location']."</div>
                    <div class='job-info'>💰 ".$job['salary']."</div>
                    <div class='job-info'>📅 ".$job['date_posted']."</div>

                    <div class='actions'>
                        <a class='btn view' href='view_applicants.php?job_id=".$job['id']."'>View</a>
                        <a class='btn edit' href='edit_job.php?id=".$job['id']."'>Edit</a>
                        <a class='btn delete' onclick=\"return confirm('Delete this job?')\" href='delete_job.php?id=".$job['id']."'>Delete</a>
                    </div>

                </div>";
            }
        } else {
            echo "<p style='color:#777;'>No jobs posted yet.</p>";
        }
        ?>

    </div>

</div>

</body>
</html>