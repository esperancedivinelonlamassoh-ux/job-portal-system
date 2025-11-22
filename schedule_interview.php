<<<<<<< HEAD
<?php
session_start();
include 'DB.php';

// Only allow organization users
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

// Accept ?id or ?application_id
$application_id = $_GET['application_id'] ?? ($_GET['id'] ?? 0);
$application_id = intval($application_id);

if ($application_id <= 0) {
    die("Invalid application ID.");
}

$org_id = $_SESSION['org_id'];

// Fetch applicant and job info
$query = "
SELECT 
    a.id AS application_id,
    a.applicant_id,
    a.cover_letter,
    ap.first_name AS applicant_name,
    ap.email AS applicant_email,
    j.title AS job_title,
    j.id AS job_id
FROM applications a
JOIN applicants ap ON a.applicant_id = ap.id
JOIN jobs j ON a.job_id = j.id
WHERE a.id = '$application_id' AND j.organization_id = '$org_id'
LIMIT 1
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("⚠️ Applicant not found or you do not have permission to schedule an interview.");
}

$applicant = mysqli_fetch_assoc($result);

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $interview_date = mysqli_real_escape_string($conn, $_POST['interview_date']);
    $interview_method = mysqli_real_escape_string($conn, $_POST['interview_method']);
    $meeting_link = mysqli_real_escape_string($conn, $_POST['meeting_link'] ?? '');
    $notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');

    $insert = "
        INSERT INTO interviews 
        (application_id, job_id, org_id, applicant_id, interview_date, interview_method, meeting_link, notes)
        VALUES
        ('{$applicant['application_id']}', '{$applicant['job_id']}', '$org_id', '{$applicant['applicant_id']}', '$interview_date', '$interview_method', '$meeting_link', '$notes')
    ";

    if (mysqli_query($conn, $insert)) {
        // Send email
        $to = $applicant['applicant_email'];
        $subject = "Interview Scheduled for {$applicant['job_title']}";
        $interview_datetime = date('d M Y, H:i', strtotime($interview_date));
        $meeting_text = $meeting_link ? "Join Link: $meeting_link" : "In-Person Interview";

        $message = "
Dear {$applicant['applicant_name']},

Your interview for the position '{$applicant['job_title']}' has been scheduled.

📅 Date & Time: $interview_datetime
🗓 Method: $interview_method
$meeting_text

Notes: $notes

Please be on time.

Best regards,
The Company
        ";

        $headers = "From: no-reply@yourdomain.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        @mail($to, $subject, $message, $headers);

        // ✅ FIXED REDIRECT (Use applicant_id)
        echo "<script>
            alert('Interview scheduled successfully and email sent to applicant!');
            window.location.href='view_applicant_details.php?applicant_id={$applicant['applicant_id']}';
        </script>";
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Schedule Interview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f0f3f5;
            font-family: Arial, sans-serif;
        }

        .flag-strip {
            height: 6px;
            background: linear-gradient(to right, #007A3D 33%, #CE1126 33%, #CE1126 66%, #FCD116 66%);
        }

        header {
            background: white;
            padding: 15px 60px;
            border-bottom: 2px solid #CE1126;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #CE1126;
            font-size: 22px;
            font-weight: bold;
        }

        nav a {
            margin-left: 20px;
            text-decoration: none;
            color: #222;
            font-weight: bold;
        }

        nav a:hover {
            color: #007A3D;
        }

        .container {
            margin-top: 40px;
            max-width: 650px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            border-left: 8px solid #007A3D;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #CE1126;
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #007A3D;
        }

        .btn-success {
            background-color: #007A3D;
            border: none;
        }

        .btn-success:hover {
            background-color: #005e2d;
        }

        .btn-secondary {
            background-color: #CE1126;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #a70d1f;
        }
    </style>
</head>

<body>

<div class="flag-strip"></div>

<header>
    <h1> Cameroon JobPortal</h1>
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="post_job.php">Post Job</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>Schedule Interview for <?php echo htmlspecialchars($applicant['applicant_name']); ?></h2>
    <p><strong style="color:#CE1126;">Job:</strong> <?php echo htmlspecialchars($applicant['job_title']); ?></p>

    <form method="post">

        <div class="mb-3">
            <label>Interview Date & Time</label>
            <input type="datetime-local" name="interview_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Interview Method</label>
            <select name="interview_method" class="form-control" required>
                <option value="In-Person">In-Person</option>
                <option value="Zoom">Zoom</option>
                <option value="Google Meet">Google Meet</option>
                <option value="Phone Call">Phone Call</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Meeting Link (if online)</label>
            <input type="url" name="meeting_link" class="form-control" placeholder="https://zoom.us/...">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Schedule Interview</button>

        <!-- ✅ FIXED CANCEL BUTTON -->
        <a href="view_applicant_details.php?applicant_id=<?php echo $applicant['applicant_id']; ?>" class="btn btn-secondary mt-2">Cancel</a>

    </form>
</div>
</body>
</html>
=======
<?php

include_once 'DB.php';

// Only allow organization users
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

// Accept ?id or ?application_id
$application_id = $_GET['application_id'] ?? ($_GET['id'] ?? 0);
$application_id = intval($application_id);

if ($application_id <= 0) {
    die("Invalid application ID.");
}

$org_id = $_SESSION['org_id'];

// Fetch applicant and job info
$query = "
SELECT 
    a.id AS application_id,
    a.applicant_id,
    a.cover_letter,
    ap.first_name AS applicant_name,
    ap.email AS applicant_email,
    j.title AS job_title,
    j.id AS job_id
FROM applications a
JOIN applicants ap ON a.applicant_id = ap.id
JOIN jobs j ON a.job_id = j.id
WHERE a.id = '$application_id' AND j.organization_id = '$org_id'
LIMIT 1
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("⚠️ Applicant not found or you do not have permission to schedule an interview.");
}

$applicant = mysqli_fetch_assoc($result);

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $interview_date = mysqli_real_escape_string($conn, $_POST['interview_date']);
    $interview_method = mysqli_real_escape_string($conn, $_POST['interview_method']);
    $meeting_link = mysqli_real_escape_string($conn, $_POST['meeting_link'] ?? '');
    $notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');

    $insert = "
        INSERT INTO interviews 
        (application_id, job_id, org_id, applicant_id, interview_date, interview_method, meeting_link, notes)
        VALUES
        ('{$applicant['application_id']}', '{$applicant['job_id']}', '$org_id', '{$applicant['applicant_id']}', '$interview_date', '$interview_method', '$meeting_link', '$notes')
    ";

    if (mysqli_query($conn, $insert)) {
        // Send email
        $to = $applicant['applicant_email'];
        $subject = "Interview Scheduled for {$applicant['job_title']}";
        $interview_datetime = date('d M Y, H:i', strtotime($interview_date));
        $meeting_text = $meeting_link ? "Join Link: $meeting_link" : "In-Person Interview";

        $message = "
Dear {$applicant['applicant_name']},

Your interview for the position '{$applicant['job_title']}' has been scheduled.

📅 Date & Time: $interview_datetime
🗓 Method: $interview_method
$meeting_text

Notes: $notes

Please be on time.

Best regards,
The Company
        ";

        $headers = "From: no-reply@yourdomain.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        @mail($to, $subject, $message, $headers);

        // ✅ FIXED REDIRECT (Use applicant_id)
        echo "<script>
            alert('Interview scheduled successfully and email sent to applicant!');
            window.location.href='view_applicant_details.php?applicant_id={$applicant['applicant_id']}';
        </script>";
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Schedule Interview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f0f3f5;
            font-family: Arial, sans-serif;
        }

        .flag-strip {
            height: 6px;
            background: linear-gradient(to right, #007A3D 33%, #CE1126 33%, #CE1126 66%, #FCD116 66%);
        }

        header {
            background: white;
            padding: 15px 60px;
            border-bottom: 2px solid #CE1126;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #CE1126;
            font-size: 22px;
            font-weight: bold;
        }

        nav a {
            margin-left: 20px;
            text-decoration: none;
            color: #222;
            font-weight: bold;
        }

        nav a:hover {
            color: #007A3D;
        }

        .container {
            margin-top: 40px;
            max-width: 650px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            border-left: 8px solid #007A3D;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #CE1126;
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #007A3D;
        }

        .btn-success {
            background-color: #007A3D;
            border: none;
        }

        .btn-success:hover {
            background-color: #005e2d;
        }

        .btn-secondary {
            background-color: #CE1126;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #a70d1f;
        }
    </style>
</head>

<body>

<div class="flag-strip"></div>

<header>
    <h1> Cameroon JobPortal</h1>
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="post_job.php">Post Job</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>Schedule Interview for <?php echo htmlspecialchars($applicant['applicant_name']); ?></h2>
    <p><strong style="color:#CE1126;">Job:</strong> <?php echo htmlspecialchars($applicant['job_title']); ?></p>

    <form method="post">

        <div class="mb-3">
            <label>Interview Date & Time</label>
            <input type="datetime-local" name="interview_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Interview Method</label>
            <select name="interview_method" class="form-control" required>
                <option value="In-Person">In-Person</option>
                <option value="Zoom">Zoom</option>
                <option value="Google Meet">Google Meet</option>
                <option value="Phone Call">Phone Call</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Meeting Link (if online)</label>
            <input type="url" name="meeting_link" class="form-control" placeholder="https://zoom.us/...">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Schedule Interview</button>

        <!-- ✅ FIXED CANCEL BUTTON -->
        <a href="view_applicant_details.php?applicant_id=<?php echo $applicant['applicant_id']; ?>" class="btn btn-secondary mt-2">Cancel</a>

    </form>
</div>
</body>
</html>
>>>>>>> 76038d0 (commit changes)
