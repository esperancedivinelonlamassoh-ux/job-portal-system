<?php
include("DB.php");
session_start();

// Restrict access to admin/organization users
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

// Fetch all contact messages
$query = "SELECT * FROM contact_messages ORDER BY date_sent DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Messages - Cameroon JobPortal</title>
<style>
    body { font-family: 'Poppins', sans-serif; background: #f6f8fa; margin: 0; }
    .sidebar { width: 250px; background: linear-gradient(180deg, #007a3d, #ce1126, #fcd116); color: white; position: fixed; top: 0; bottom: 0; padding: 20px; }
    .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 0; margin-bottom: 10px; font-weight: 500; }
    .main { margin-left: 260px; padding: 30px; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
    th { background: #007a3d; color: white; }
    tr:hover { background: #fcfcfc; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Cameroon JobPortal</h2>
    <a href="admin.php">🏠 Dashboard</a>
    <a href="adminmessage.php">📩 Messages</a>
    <a href="post_job.php">📝 Post New Job</a>
    <a href="view_interview.php">📅 View Interviews</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <h1>Received Messages</h1>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date Sent</th>
            </tr>
            <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo $row['date_sent']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No messages received yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
