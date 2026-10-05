<?php
session_start();
include 'DB.php';

if (!isset($_SESSION['applicant_id'])) {
    header('Location: login_applicant.php');
    exit;
}

$applicant_id = $_SESSION['applicant_id'];
$success_msg = '';
$error_msg = '';

/* REPLY */
if (isset($_POST['reply'])) {
    $message_id = intval($_POST['message_id']);
    $reply_text = mysqli_real_escape_string($conn, $_POST['reply_text']);

    $msg_query = "SELECT sender_id, subject 
                  FROM messages 
                  WHERE id='$message_id' 
                  AND receiver_type='applicant' 
                  AND receiver_id='$applicant_id'";

    $msg_result = mysqli_query($conn, $msg_query);

    if (mysqli_num_rows($msg_result) > 0) {
        $msg = mysqli_fetch_assoc($msg_result);
        $org_id = $msg['sender_id'];
        $subject = "Re: " . ($msg['subject'] ?: 'Message');

        mysqli_query($conn, "INSERT INTO messages 
        (sender_type, sender_id, receiver_type, receiver_id, subject, message)
        VALUES ('applicant','$applicant_id','org','$org_id','$subject','$reply_text')");

        $success_msg = "✅ Reply sent!";
    }
}

/* INTERVIEWS */
$interview_query = "
SELECT i.*, j.title AS job_title, o.name AS org_name
FROM interviews i
JOIN jobs j ON i.job_id = j.id
JOIN organizations o ON i.org_id = o.id
WHERE i.applicant_id='$applicant_id'
ORDER BY i.interview_date DESC
";
$interview_result = mysqli_query($conn, $interview_query);

/* MESSAGES */
$message_query = "
SELECT m.*, o.name AS org_name
FROM messages m
JOIN organizations o ON m.sender_id = o.id
WHERE m.receiver_type='applicant' 
AND m.receiver_id='$applicant_id'
ORDER BY date_sent DESC
";
$message_result = mysqli_query($conn, $message_query);

$total_interviews = mysqli_num_rows($interview_result);
$total_messages = mysqli_num_rows($message_result);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Applicant Dashboard</title>

<style>
body {
    margin:0;
    font-family:'Poppins', sans-serif;
    display:flex;
    background:#f4f6f9;
}

/* SIDEBAR */
.sidebar {
    width:250px;
    background:linear-gradient(180deg,#007a3d,#ce1126,#fcd116);
    color:white;
    padding:20px;
    height:100vh;
    position:fixed;
}
.sidebar a {
    display:block;
    color:white;
    padding:12px;
    text-decoration:none;
    margin-bottom:10px;
    border-radius:8px;
}
.sidebar a:hover {
    background:rgba(255,255,255,0.2);
}

/* MAIN */
.main {
    margin-left:260px;
    padding:30px;
    width:100%;
}

/* STATS */
.stats {
    display:flex;
    gap:20px;
    margin-bottom:25px;
}
.card {
    flex:1;
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

/* INTERVIEW CARD */
.interview {
    background:white;
    padding:20px;
    margin-bottom:15px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}
.join-btn {
    display:inline-block;
    background:#007a3d;
    color:white;
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    margin-top:10px;
}
.join-btn:hover { background:#005e2e; }

/* MESSAGE */
.message {
    background:white;
    padding:15px;
    margin-bottom:15px;
    border-radius:10px;
}
textarea {
    width:100%;
    margin-top:5px;
}
button {
    margin-top:5px;
    background:#007a3d;
    color:white;
    border:none;
    padding:6px 10px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>JobPortal</h2>
    <a href="#">🏠 Dashboard</a>
    <a href="jobs.php">Jobs</a>
    <a href="applied_jobs.php">Applied Jobs</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<h1>Welcome 👋</h1>

<!-- STATS -->
<div class="stats">
    <div class="card">
        <h3>Interviews</h3>
        <p><?= $total_interviews ?></p>
    </div>
    <div class="card">
        <h3>Messages</h3>
        <p><?= $total_messages ?></p>
    </div>
</div>

<!-- INTERVIEWS -->
<h2>📅 Upcoming Interviews</h2>

<?php if ($total_interviews > 0): ?>
    <?php mysqli_data_seek($interview_result,0); ?>
    <?php while($i = mysqli_fetch_assoc($interview_result)): ?>
        <div class="interview">
            <strong><?= $i['job_title'] ?></strong> - <?= $i['org_name'] ?><br>
            📅 <?= date('d M Y H:i', strtotime($i['interview_date'])) ?><br>
            📌 <?= $i['interview_method'] ?><br>

            <?php if(!empty($i['meeting_link'])): ?>
                <a class="join-btn" href="<?= $i['meeting_link'] ?>" target="_blank">
                    🎥 Join Interview
                </a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No interviews yet</p>
<?php endif; ?>

<!-- MESSAGES -->
<h2>💬 Messages</h2>

<?php if($success_msg) echo "<p>$success_msg</p>"; ?>

<?php while($msg = mysqli_fetch_assoc($message_result)): ?>
<div class="message">
    <strong><?= $msg['org_name'] ?></strong><br>
    <?= $msg['message'] ?><br>

    <form method="POST">
        <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
        <textarea name="reply_text" placeholder="Reply..." required></textarea>
        <button name="reply">Send</button>
    </form>
</div>
<?php endwhile; ?>

</div>
</body>
</html>