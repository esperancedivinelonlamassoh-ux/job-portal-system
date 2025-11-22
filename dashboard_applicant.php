
<?php
session_start();
include 'DB.php';

// ✅ Check if applicant is logged in
if (!isset($_SESSION['applicant_id'])) {
    header('Location: login_applicant.php');
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

// ✅ Fetch all interviews for this applicant
$query = "SELECT i.*, j.title AS job_title, o.name AS org_name
          FROM interviews i
          JOIN jobs j ON i.job_id = j.id
          JOIN organizations o ON i.org_id = o.id
          WHERE i.applicant_id='$applicant_id'
          ORDER BY i.interview_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Interviews 🇨🇲 - JobConnect CM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
 <style>
    body {
        background-color: #eef2f3;
        font-family: "Poppins", Arial, sans-serif;
    }

    /* ===== HEADER WITH CAMEROON VIBE ===== */
    header {
        background: linear-gradient(90deg, #007b3e, #d40000, #fcd116);
        padding: 18px 45px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        position: relative;
    }

    /* African pattern overlay */
    header::after {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('https://i.imgur.com/7QZQ2kH.png'); /* light African pattern */
        opacity: 0.12;
        pointer-events: none;
    }

    header h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 1px;
        color: #fff;
        z-index: 2;
    }

    nav a {
        margin-left: 25px;
        text-decoration: none;
        font-weight: 600;
        color: #fff;
        z-index: 2;
        position: relative;
        padding-bottom: 4px;
    }

    nav a:hover,
    nav a.active {
        color: #ffd700;
        border-bottom: 2px solid #ffd700;
    }

    /* ===== PAGE TITLE ===== */
    h2 {
        text-align: center;
        color: #007b3e;
        font-weight: 700;
        margin: 35px 0;
        font-size: 28px;
    }

    .container {
        max-width: 1100px;
    }

    /* ===== TABLE STYLING ===== */
    table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    }

    th {
        background: #007b3e;
        color: #fff;
        font-size: 15px;
        letter-spacing: 0.3px;
        padding: 14px;
    }

    td {
        padding: 13px;
        font-size: 14px;
        color: #333;
    }

    tr:nth-child(even) { background-color: #f9fafb; }
    tr:hover { background-color: #f1f3f4; }

    /* ===== STATUS BADGES (MORE CAMEROON STYLE) ===== */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
    }

    .status-scheduled { background: #004aad; color: white; }
    .status-completed { background: #008a2e; color: white; }
    .status-canceled  { background: #c10000; color: white; }

    /* ===== FOOTER ===== */
    footer {
        background: #007b3e;
        color: #fff;
        text-align: center;
        padding: 18px;
        margin-top: 60px;
        border-top: 4px solid #fcd116;
        font-size: 15px;
        font-weight: 500;
    }

    /* ===== MOBILE FRIENDLY ===== */
    @media (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: flex-start;
            padding: 18px 25px;
        }
        nav {
            margin-top: 10px;
        }
        nav a {
            margin-left: 0;
            margin-right: 15px;
        }
    }
</style>

</head>
<body>
<header>
    <h1>Cameroon JobPortal</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="jobs.php">Jobs</a>
        <a href="recommended_jobs.php">Recommended Jobs</a>
        <a href="applied_jobs.php">Applied Jobs</a>
        <a href="dashboard_applicant.php" class="active">Dashboard</a>
    </nav>
</header>

<div class="container">
    <h2>📅 My Scheduled Interviews</h2>
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
        if (mysqli_num_rows($result) > 0) {
            while ($interview = mysqli_fetch_assoc($result)) {
                $status_class = '';
                switch($interview['status']){
                    case 'scheduled': $status_class = 'status-scheduled'; break;
                    case 'completed': $status_class = 'status-completed'; break;
                    case 'canceled': $status_class = 'status-canceled'; break;
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
        } else {
            echo "<tr><td colspan='6' class='text-center'>No interviews scheduled yet. Start applying to Cameroonian companies today!</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<footer>
    &copy; <?= date('Y') ?> JobConnect CM 🇨🇲 — Empowering Cameroonians to find better work opportunities
</footer>
</body>
</html>
