<?php
session_start();
include 'DB.php';

// ✅ Check if applicant is logged in
if (!isset($_SESSION['applicant_id'])) {
    header('Location: login_applicant.php');
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

// ===== Handle reply to admin =====
$success_msg = '';
$error_msg = '';
if (isset($_POST['reply'])) {
    $message_id = $_POST['message_id'];
    $reply_text = mysqli_real_escape_string($conn, $_POST['reply_text']);

    // Get original message info
    $msg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sender_id, subject FROM messages WHERE id='$message_id'"));
    $org_id = $msg['sender_id'];
    $subject = "Re: " . $msg['subject'];

    $sql = "INSERT INTO messages (sender_type, sender_id, receiver_type, receiver_id, subject, message)
            VALUES ('applicant', '$applicant_id', 'org', '$org_id', '$subject', '$reply_text')";
    if (mysqli_query($conn, $sql)) {
        $success_msg = "✅ Reply sent successfully!";
    } else {
        $error_msg = "⚠️ Failed to send reply.";
    }
}

// ===== Fetch applicant interviews =====
$interview_query = "SELECT i.*, j.title AS job_title, o.name AS org_name
          FROM interviews i
          JOIN jobs j ON i.job_id = j.id
          JOIN organizations o ON i.org_id = o.id
          WHERE i.applicant_id='$applicant_id'
          ORDER BY i.interview_date DESC";
$interview_result = mysqli_query($conn, $interview_query);

// ===== Fetch applicant messages =====
$message_query = "SELECT m.*, o.name AS org_name
                  FROM messages m
                  JOIN organizations o ON m.sender_id = o.id
                  WHERE m.receiver_type='applicant' AND m.receiver_id='$applicant_id'
                  ORDER BY m.date_sent DESC";
$message_result = mysqli_query($conn, $message_query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Applicant Dashboard 🇨🇲 - JobConnect CM</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
body { font-family:'Poppins', sans-serif; margin:0; background:#eef2f3; display:flex; }
.sidebar { width:250px; background:linear-gradient(180deg, #007a3d, #ce1126, #fcd116); color:white; padding:20px; height:100vh; position:fixed; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; color:white; text-decoration:none; padding:12px 15px; margin-bottom:10px; border-radius:8px; font-weight:500; transition:0.3s; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); transform:translateX(5px); }
.main { margin-left:260px; padding:30px; flex-grow:1; }

h1 { color:#007a3d; margin-bottom:20px; font-size:28px; }
h2 { color:#007a3d; margin-bottom:15px; }

table { width:100%; background:white; border-radius:10px; overflow:hidden; box-shadow:0 3px 10px rgba(0,0,0,0.1); margin-bottom:30px; }
th, td { padding:12px; border-bottom:1px solid #eee; text-align:left; vertical-align:middle; }
th { background:#007a3d; color:white; }
tr:hover { background:#fcfcfc; }
textarea { width:100%; padding:8px; border-radius:5px; border:1px solid #ccc; margin-bottom:5px; }
button { background:#007a3d; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer; }
button:hover { background:#005e2e; }
.success { color:green; font-weight:bold; margin-bottom:10px; }
.error { color:red; font-weight:bold; margin-bottom:10px; }

.status-scheduled { background:#004aad; color:white; padding:4px 8px; border-radius:5px; }
.status-completed { background:#008a2e; color:white; padding:4px 8px; border-radius:5px; }
.status-canceled { background:#c10000; color:white; padding:4px 8px; border-radius:5px; }

</style>
</head>
<body>

<div class="sidebar">
    <h2>Cameroon JobPortal</h2>
    <a href="dashboard_applicant.php" class="active">🏠 Dashboard</a>
    <a href="message_applicant.php">💬 Messages</a>
    <a href="jobs.php">Jobs</a>
    <a href="applied_jobs.php">Applied Jobs</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <h1>📅 My Scheduled Interviews</h1>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Organization</th>
                <th>Date & Time</th>
                <th>Method</th>
                <th>Notes</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if(mysqli_num_rows($interview_result) > 0){
            while($interview = mysqli_fetch_assoc($interview_result)){
                $status_class='';
                switch($interview['status']){
                    case 'scheduled': $status_class='status-scheduled'; break;
                    case 'completed': $status_class='status-completed'; break;
                    case 'canceled': $status_class='status-canceled'; break;
                }
                echo "<tr>
                        <td>".htmlspecialchars($interview['job_title'])."</td>
                        <td>".htmlspecialchars($interview['org_name'])."</td>
                        <td>".date('d M Y, H:i', strtotime($interview['interview_date']))."</td>
                        <td>".htmlspecialchars($interview['interview_method'])."</td>
                        <td>".htmlspecialchars($interview['notes'])."</td>
                        <td class='$status_class'>".ucfirst($interview['status'])."</td>
                      </tr>";
            }
        }else{
            echo "<tr><td colspan='6' class='text-center'>No interviews scheduled yet.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <h1>💬 Messages</h1>
    <?php if($success_msg) echo "<div class='success'>$success_msg</div>"; ?>
    <?php if($error_msg) echo "<div class='error'>$error_msg</div>"; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>From (Organization)</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date Sent</th>
                <th>Reply</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($message_result) > 0): ?>
            <?php while($msg = mysqli_fetch_assoc($message_result)): ?>
            <tr>
                <td><?= htmlspecialchars($msg['org_name']) ?></td>
                <td><?= htmlspecialchars($msg['subject']) ?></td>
                <td><?= htmlspecialchars($msg['message']) ?></td>
                <td><?= date('d M Y, H:i', strtotime($msg['date_sent'])) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                        <textarea name="reply_text" rows="2" placeholder="Type reply..." required></textarea>
                        <button type="submit" name="reply">Reply</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No messages yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
