
<?php
session_start();
include 'DB.php';

if(!isset($_GET['job_id'])){
    die("Job ID not specified.");
}

$job_id = intval($_GET['job_id']);

// Fetch applicants for this job only
$query = "
    SELECT 
        a.id AS applicant_id, 
        a.first_name, 
        a.email, 
        ap.status, 
        ap.date_applied,
        j.title AS job_title
    FROM applicants a
    JOIN applications ap ON a.id = ap.applicant_id
    JOIN jobs j ON ap.job_id = j.id
    WHERE ap.job_id = ?
    ORDER BY ap.date_applied DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

// Get job title
$job_title = "Unknown";
if ($result->num_rows > 0) {
    $row_temp = $result->fetch_assoc();
    $job_title = $row_temp['job_title'];
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applicants for Job</title>
<style>
body {
    font-family: "Poppins", Arial, sans-serif;
    background-color: #eef2f3;
    margin: 0;
}

header {
    background: linear-gradient(90deg, #007b3e, #ce1126, #fcd116);
    padding: 16px 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

header .logo {
    font-size: 24px;
    font-weight: 900;
    text-decoration: none;
    color: #fff;
}

nav a {
    margin-left: 25px;
    text-decoration: none;
    color: white;
    font-weight: 600;
}

nav a:hover {
    color: #ffd700;
}

.container {
    max-width: 1100px;
    margin: 50px auto;
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

h2 {
    text-align: center;
    color: #007b3e;
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 25px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #007b3e;
    color: white;
    padding: 14px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #e5e5e5;
}

tr:nth-child(even) {
    background: #f9fafb;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
    color: white;
}

.status-pending { background: #004aad; }
.status-reviewed { background: #ce1126; }
.status-shortlisted { background: #007b3e; }
.status-hired { background: #fcd116; color: #000; }

.action-btn {
    padding: 7px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    border: none;
}

.view { background: #007b3e; color: white; }
.view:hover { background: #005f2a; }

footer {
    text-align: center;
    padding: 20px;
    margin-top: 50px;
    background: #007b3e;
    color: white;
}
</style>

</head>
<body>

<header>
  <a href="home.php" class="logo">Cameroon JobPortal</a>
  <nav>
    <a href="admin.php">Dashboard</a>
    <a href="post_job.php">Post New Job</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
    <h2>Applicants for Job: <?= htmlspecialchars($job_title); ?></h2>

    <table>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Date Applied</th>
            <th>Actions</th>
        </tr>

        <?php
        $i = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $status = strtolower($row['status']);
                $badge = "status-badge status-" . $status;

                echo "
                <tr>
                    <td>{$i}</td>
                    <td>".htmlspecialchars($row['first_name'])."</td>
                    <td>".htmlspecialchars($row['email'])."</td>
                    <td><span class='$badge'>".htmlspecialchars($row['status'])."</span></td>
                    <td>".htmlspecialchars($row['date_applied'])."</td>
                    <td>
                        <a href='view_applicant_details.php?applicant_id={$row['applicant_id']}&job_id={$job_id}'>
                            <button class='action-btn view'>View Details</button>
                        </a>
                    </td>
                </tr>";
                $i++;
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>No applicants found for this job.</td></tr>";
        }
        ?>
    </table>
</div>

<footer>
    &copy; <?= date('Y'); ?> JobPortal - Connecting Talent with Opportunities
</footer>

</body>
</html>
