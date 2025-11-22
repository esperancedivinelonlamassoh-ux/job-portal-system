
<?php
include 'DB.php';
session_start();

// Correct variable: use applicant_id NOT id
$applicant_id = intval($_GET['applicant_id'] ?? 0);

if ($applicant_id <= 0) {
    die("Invalid applicant ID");
}

// Fetch applicant & application details including job_id
$query = "
SELECT 
    ap.id AS application_id,
    ap.job_id,
    a.first_name AS applicant_name,
    a.email AS applicant_email,
    a.phone AS applicant_phone,
    j.title AS job_title,
    ap.cover_letter,
    ap.cv,
    ap.id_card
FROM applications ap
JOIN applicants a ON ap.applicant_id = a.id
JOIN jobs j ON ap.job_id = j.id
WHERE ap.applicant_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Applicant details not found.");
}

$applicant = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Applicant Details</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
    body {
        background-color: #eef2f3;
        font-family: "Poppins", Arial, sans-serif;
        margin: 0;
    }

    header {
        background: linear-gradient(90deg, #007b3e, #ce1126, #fcd116);
        padding: 18px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        color: white;
    }

    header::after {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('https://i.imgur.com/7QZQ2kH.png');
        opacity: 0.12;
        pointer-events: none;
    }

    .logo {
        font-size: 24px;
        font-weight: 900;
        color: #fff;
        z-index: 2;
        text-decoration: none;
    }

    nav a {
        margin-left: 25px;
        text-decoration: none;
        font-weight: 600;
        color: white;
        z-index: 2;
        padding-bottom: 4px;
    }

    nav a:hover {
        color: #ffd700;
        border-bottom: 2px solid #ffd700;
    }

    .container {
        max-width: 900px;
        background: white;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        margin: 60px auto;
    }

    h2 {
        text-align: center;
        color: #007b3e;
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 30px;
    }

    .info-section {
        background-color: #f9fafb;
        border-left: 6px solid #ce1126;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        margin-bottom: 15px;
        color: #333;
    }

    p strong {
        color: #007b3e;
        font-weight: 700;
    }

    .btn-custom {
        padding: 8px 16px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 6px;
        margin-right: 10px;
    }

    .btn-success { background-color: #007b3e !important; }
    .btn-success:hover { background-color: #005f2a !important; }
    .btn-secondary { background-color: #ce1126 !important; }
    .btn-secondary:hover { background-color: #a50d1f !important; }

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
    <h2>Applicant Details</h2>

    <div class="info-section">
        <p><strong>Name:</strong> <?= htmlspecialchars($applicant['applicant_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($applicant['applicant_email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($applicant['applicant_phone']) ?></p>
        <p><strong>Applied for:</strong> <?= htmlspecialchars($applicant['job_title']) ?></p>
        <p><strong>Cover Letter:</strong><br><?= nl2br(htmlspecialchars($applicant['cover_letter'])) ?></p>
        <p><strong>CV:</strong> <a href="<?= htmlspecialchars($applicant['cv']) ?>" target="_blank">Download CV</a></p>
        <p><strong>ID Card:</strong> <a href="<?= htmlspecialchars($applicant['id_card']) ?>" target="_blank">View ID</a></p>
    </div>

    <div class="actions" style="margin-top: 20px;">

        <!-- ✅ FIXED: pass application_id using correct parameter -->
        <a href="schedule_interview.php?application_id=<?= $applicant['application_id'] ?>" 
           class="btn btn-success btn-custom">
            Schedule Interview
        </a>

        <!-- Back button correct -->
        <a href="view_applicants.php?job_id=<?= $applicant['job_id'] ?>" 
           class="btn btn-secondary btn-custom">
            Back to Applicants
        </a>

    </div>
</div>

</body>
</html>
