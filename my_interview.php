
<?php
include("DB.php");
session_start();

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'applicant'){
    header('Location: login.php');
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

$query = "SELECT i.*, j.title AS job_title
          FROM interviews i
          JOIN jobs j ON i.job_id = j.id
          WHERE i.applicant_id = '$applicant_id'
          ORDER BY i.interview_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Interviews</title>
<style>
body { font-family: Arial, sans-serif; background:#f5f6fa; padding:20px; }
table { width:100%; border-collapse:collapse; background:white; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left;}
th { background:#004aad; color:white; }
tr:hover { background:#f1f1f1; }
a.join-btn { background:#17a2b8; color:white; padding:6px 10px; border-radius:4px; text-decoration:none; }
a.join-btn:hover { background:#138496; }
</style>
</head>
<body>

<h2>My Scheduled Interviews</h2>

<table>
<tr>
    <th>Job</th>
    <th>Date & Time</th>
    <th>Meeting Link</th>
    <th>Status</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['job_title'])."</td>";
        echo "<td>".htmlspecialchars($row['interview_date'])."</td>";
        echo "<td><a href='".htmlspecialchars($row['meeting_link'])."' target='_blank' class='join-btn'>Join Interview</a></td>";
        echo "<td>".htmlspecialchars($row['status'])."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No interviews scheduled yet.</td></tr>";
}
?>
</table>

</body>
</html>
=======
<?php
include_once("DB.php");


if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'applicant'){
    header('Location: login.php');
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

$query = "SELECT i.*, j.title AS job_title
          FROM interviews i
          JOIN jobs j ON i.job_id = j.id
          WHERE i.applicant_id = '$applicant_id'
          ORDER BY i.interview_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Interviews</title>
<style>
body { font-family: Arial, sans-serif; background:#f5f6fa; padding:20px; }
table { width:100%; border-collapse:collapse; background:white; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left;}
th { background:#004aad; color:white; }
tr:hover { background:#f1f1f1; }
a.join-btn { background:#17a2b8; color:white; padding:6px 10px; border-radius:4px; text-decoration:none; }
a.join-btn:hover { background:#138496; }
</style>
</head>
<body>

<h2>My Scheduled Interviews</h2>

<table>
<tr>
    <th>Job</th>
    <th>Date & Time</th>
    <th>Meeting Link</th>
    <th>Status</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['job_title'])."</td>";
        echo "<td>".htmlspecialchars($row['interview_date'])."</td>";
        echo "<td><a href='".htmlspecialchars($row['meeting_link'])."' target='_blank' class='join-btn'>Join Interview</a></td>";
        echo "<td>".htmlspecialchars($row['status'])."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No interviews scheduled yet.</td></tr>";
}
?>
</table>

</body>
</html>

