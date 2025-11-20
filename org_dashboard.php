<?php
include("DB.php");
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

$query = "SELECT i.*, CONCAT(a.applicant_id) AS applicant_name, j.title AS job_title
          FROM interviews i
          JOIN applications a ON i.applicant_id = a.id
          JOIN jobs j ON i.job_id = j.id
          WHERE j.organization_id = '$org_id'
          ORDER BY i.interview_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>JobPortal Cameroon – Manage Interviews</title>

<style>
/* BACKGROUND ANIMATION */
body {
  margin: 0;
  font-family: "Poppins", Arial, sans-serif;
  background: linear-gradient(135deg, #006b3f, #d21034, #ffcc00);
  background-size: 400% 400%;
  animation: cameroonFlow 12s ease infinite;
}
@keyframes cameroonFlow {
  0% {background-position: 0% 50%;}
  50% {background-position: 100% 50%;}
  100% {background-position: 0% 50%;}
}

/* SIDEBAR */
.sidebar {
  width: 250px;
  background: rgba(0,0,0,0.85);
  color: white;
  padding: 25px;
  position: fixed;
  top: 0;
  bottom: 0;
  box-shadow: 4px 0 10px rgba(0,0,0,0.3);
}
.sidebar h2 {
  text-align: center;
  margin-bottom: 40px;
  color: #ffcc00;
  text-transform: uppercase;
}
.sidebar a {
  display: block;
  padding: 12px;
  margin-bottom: 10px;
  text-decoration: none;
  color: white;
  border-radius: 8px;
  transition: 0.3s;
}
.sidebar a:hover {
  background: #006b3f;
}

/* MAIN CONTENT */
.main {
  margin-left: 270px;
  padding: 30px;
}

/* TITLE */
h1 {
  color: white;
  text-shadow: 0 2px 6px rgba(0,0,0,0.3);
  margin-bottom: 20px;
  text-transform: uppercase;
}

/* BUTTON */
.btn {
  padding: 10px 14px;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
}
.schedule-btn { background: #006b3f; }
.schedule-btn:hover { background: #d21034; }

.edit-btn { background: #ffcc00; color:black; }
.edit-btn:hover { background: #d21034; color:white; }

.delete-btn { background:#d21034; }
.delete-btn:hover { background:#a50f28; }

/* TABLE */
table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
th {
  background: #004aad;
  color: white;
  padding: 14px;
  text-transform: uppercase;
}
td {
  padding: 12px;
  border-bottom: 1px solid #eee;
}
tr:hover {
  background: #f8f8f8;
}
</style>

</head>

<body>

<div class="sidebar">
    <h2>🇨🇲 JobPortal</h2>
    <a href="admin.php">Dashboard</a>
    <a href="post_job.php">Post New Job</a>
    <a href="view_interviews.php" style="background:#006b3f;">Manage Interviews</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h1>Scheduled Interviews</h1>

    <a href="schedule_interview.php" class="btn schedule-btn">+ Schedule New Interview</a><br><br>

    <table>
        <tr>
            <th>Applicant</th>
            <th>Job</th>
            <th>Date & Time</th>
            <th>Meeting Link</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".htmlspecialchars($row['applicant_name'])."</td>";
                echo "<td>".htmlspecialchars($row['job_title'])."</td>";
                echo "<td>".htmlspecialchars($row['interview_date'])."</td>";
                echo "<td><a href='".htmlspecialchars($row['meeting_link'])."' target='_blank'>Join</a></td>";
                echo "<td>".htmlspecialchars($row['status'])."</td>";
                echo "<td>
                        <a href='edit_interview.php?id=".$row['id']."' class='btn edit-btn'>Edit</a>
                        <a href='delete_interview.php?id=".$row['id']."' class='btn delete-btn' onclick=\"return confirm('Are you sure?')\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center; padding:20px;'>No interviews scheduled yet.</td></tr>";
        }
        ?>
    </table>

</div>

</body>
</html>
