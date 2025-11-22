
<?php
include("DB.php");
session_start();

// Check if org is logged in
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

// ✅ FIXED QUERY
$query = "SELECT i.*, 
                 CONCAT(app.first_name, ' ', app.last_name) AS applicant_name, 
                 j.title AS job_title
          FROM interviews i
          JOIN applicants app ON i.applicant_id = app.id
          JOIN jobs j ON i.job_id = j.id
          WHERE j.organization_id = '$org_id'
          ORDER BY i.interview_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Interviews</title>
<style>
* { box-sizing: border-box; font-family: "Poppins", Arial, sans-serif; }
body { 
    margin:0; 
    display:flex; 
    min-height:100vh; 
    background:#f8f9fa 
}

:root {
    --cam-green: #007A5E;
    --cam-red: #CE1126;
    --cam-yellow: #FCD116;
}

.sidebar { 
    width:250px; 
    background: var(--cam-green);
    color:white; 
    padding:20px; 
    position:fixed; 
    top:0; 
    bottom:0; 
    box-shadow: 2px 0 8px rgba(0,0,0,0.2);
    background-image: linear-gradient(180deg, var(--cam-green), #005A45);
}
.sidebar h2 { 
    text-align:center; 
    margin-bottom:30px; 
    font-size:24px;
    font-weight:bold;
    text-shadow: 1px 1px 2px black;
}
.sidebar a { 
    display:block; 
    color:white; 
    text-decoration:none; 
    padding:12px; 
    margin-bottom:10px; 
    border-radius:6px; 
    font-size:15px;
    background: rgba(255,255,255,0.1);
    transition: 0.3s;
}
.sidebar a:hover { 
    background: var(--cam-yellow); 
    color:black;
    font-weight:bold;
}

.main { 
    margin-left:260px; 
    padding:20px; 
    flex-grow:1; 
}
h1 { 
    color:var(--cam-red); 
    margin-bottom:20px; 
    font-size:28px;
    border-left: 6px solid var(--cam-yellow);
    padding-left:10px;
}

.btn { 
    padding:8px 12px; 
    text-decoration:none; 
    color:white; 
    border-radius:4px; 
    font-size:14px;
}

.schedule-btn { background: var(--cam-green); }
.schedule-btn:hover { background:#005A45; }

.edit-btn { background:var(--cam-yellow); color:black; }
.edit-btn:hover { background:#e0b300; }

.delete-btn { background:var(--cam-red); }
.delete-btn:hover { background:#a50d1c; }

table { 
    width:100%; 
    border-collapse:collapse; 
    background:white; 
    box-shadow:0 2px 8px rgba(0,0,0,0.15);
    border-radius:6px;
    overflow:hidden;
}
th, td { 
    padding:14px; 
    border-bottom:1px solid #eee; 
    font-size:15px;
}
th { 
    background:var(--cam-yellow); 
    color:black; 
    font-weight:bold;
}
tr:hover { background:#f9f9f9; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Cameroon JobPortal </h2>
    <a href="admin.php">Dashboard</a>
    <a href="post_job.php">Post New Job</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h1>Scheduled Interviews</h1>

    <a href="schedule_interview.php" class="btn schedule-btn">+ Schedule New Interview</a>

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
            echo "<tr><td colspan='6'>No interviews scheduled yet.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
