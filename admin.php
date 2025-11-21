<<<<<<< HEAD
<?php
include("DB.php");
session_start();

// Restrict access to organization users only
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

// Fetch all jobs for this organization
$query = "SELECT * FROM jobs WHERE organization_id = '$org_id' ORDER BY date_posted DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - JobPortal Cameroon 🇨🇲</title>

  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { margin: 0; display: flex; min-height: 100vh; background: #f5f6fa; }

    /* SIDEBAR */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #007a3d, #ce1126, #fcd116);
      color: white;
      padding: 20px;
      position: fixed;
      top: 0;
      bottom: 0;
      box-shadow: 2px 0 10px rgba(0,0,0,0.2);
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 22px;
      font-weight: 700;
    }
    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      font-weight: 500;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.2);
      transform: translateX(5px);
    }

    /* MAIN CONTENT */
    .main {
      margin-left: 260px;
      padding: 30px;
      flex-grow: 1;
    }

    h1 {
      color: #007a3d;
      margin-bottom: 20px;
      font-size: 28px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    h1::before {
      content: "🇨🇲";
    }

    .post-btn {
      display: inline-block;
      background: #007a3d;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      margin-bottom: 25px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }
    .post-btn:hover {
      background: #005e2e;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 14px;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #007a3d;
      color: white;
      text-transform: uppercase;
      font-size: 14px;
    }
    tr:hover {
      background: #fcfcfc;
    }

    td img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    /* ACTION BUTTONS */
    .btn {
      padding: 6px 10px;
      text-decoration: none;
      color: white;
      border-radius: 5px;
      font-size: 13px;
    }
    .view-btn { background: #17a2b8; }
    .edit-btn { background: #28a745; }
    .delete-btn { background: #dc3545; }
    .btn:hover { opacity: 0.85; }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 15px;
      color: #555;
      margin-top: 30px;
      font-size: 13px;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Cameroon JobPortal</h2>
    <a href="admin.php">🏠 Dashboard</a>
    <a href="post_job.php">📝 Post New Job</a>
    <a href="view_interview.php">📅 View Interviews</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main">
    <h1>Your Posted Jobs</h1>
    <a href="post_job.php" class="post-btn">+ Post a New Job</a>

    <table>
      <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Location</th>
        <th>Salary</th>
        <th>Date Posted</th>
        <th>Actions</th>
      </tr>

      <?php
      if (mysqli_num_rows($result) > 0) {
          while ($job = mysqli_fetch_assoc($result)) {

              $img = !empty($job['image']) ? $job['image'] : "default.jpg";

              echo "<tr>
                      <td><img src='" . htmlspecialchars($img) . "'></td>
                      <td>" . htmlspecialchars($job['title']) . "</td>
                      <td>" . htmlspecialchars($job['location']) . "</td>
                      <td>" . htmlspecialchars($job['salary']) . "</td>
                      <td>" . htmlspecialchars($job['date_posted']) . "</td>
                      <td>
                        <a class='btn view-btn' href='view_applicants.php?job_id=" . $job['id'] . "'>View</a>
                        <a class='btn edit-btn' href='edit_job.php?id=" . $job['id'] . "'>Edit</a>
                        <a class='btn delete-btn' onclick=\"return confirm('Delete this job?')\" href='delete_job.php?id=" . $job['id'] . "'>Delete</a>
                      </td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='6' style='text-align:center;color:#777;'>No jobs posted yet.</td></tr>";
      }
      ?>
    </table>

    <footer>
      © <?php echo date("Y"); ?> JobPortal Cameroon — Empowering Local Opportunities 🇨🇲
    </footer>
  </div>

</body>
</html>
=======
<?php
include("DB.php");
session_start();

// Restrict access to organization users only
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

// Fetch all jobs for this organization
$query = "SELECT * FROM jobs WHERE organization_id = '$org_id' ORDER BY date_posted DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - JobPortal Cameroon 🇨🇲</title>

  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { margin: 0; display: flex; min-height: 100vh; background: #f5f6fa; }

    /* SIDEBAR */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #007a3d, #ce1126, #fcd116);
      color: white;
      padding: 20px;
      position: fixed;
      top: 0;
      bottom: 0;
      box-shadow: 2px 0 10px rgba(0,0,0,0.2);
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 22px;
      font-weight: 700;
    }
    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      font-weight: 500;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.2);
      transform: translateX(5px);
    }

    /* MAIN CONTENT */
    .main {
      margin-left: 260px;
      padding: 30px;
      flex-grow: 1;
    }

    h1 {
      color: #007a3d;
      margin-bottom: 20px;
      font-size: 28px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    h1::before {
      content: "🇨🇲";
    }

    .post-btn {
      display: inline-block;
      background: #007a3d;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      margin-bottom: 25px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }
    .post-btn:hover {
      background: #005e2e;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 14px;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #007a3d;
      color: white;
      text-transform: uppercase;
      font-size: 14px;
    }
    tr:hover {
      background: #fcfcfc;
    }

    td img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    /* ACTION BUTTONS */
    .btn {
      padding: 6px 10px;
      text-decoration: none;
      color: white;
      border-radius: 5px;
      font-size: 13px;
    }
    .view-btn { background: #17a2b8; }
    .edit-btn { background: #28a745; }
    .delete-btn { background: #dc3545; }
    .btn:hover { opacity: 0.85; }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 15px;
      color: #555;
      margin-top: 30px;
      font-size: 13px;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Cameroon JobPortal</h2>
    <a href="admin.php">🏠 Dashboard</a>
    <a href="post_job.php">📝 Post New Job</a>
    <a href="view_interview.php">📅 View Interviews</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main">
    <h1>Your Posted Jobs</h1>
    <a href="post_job.php" class="post-btn">+ Post a New Job</a>

    <table>
      <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Location</th>
        <th>Salary</th>
        <th>Date Posted</th>
        <th>Actions</th>
      </tr>

      <?php
      if (mysqli_num_rows($result) > 0) {
          while ($job = mysqli_fetch_assoc($result)) {

              $img = !empty($job['image']) ? $job['image'] : "default.jpg";

              echo "<tr>
                      <td><img src='" . htmlspecialchars($img) . "'></td>
                      <td>" . htmlspecialchars($job['title']) . "</td>
                      <td>" . htmlspecialchars($job['location']) . "</td>
                      <td>" . htmlspecialchars($job['salary']) . "</td>
                      <td>" . htmlspecialchars($job['date_posted']) . "</td>
                      <td>
                        <a class='btn view-btn' href='view_applicants.php?job_id=" . $job['id'] . "'>View</a>
                        <a class='btn edit-btn' href='edit_job.php?id=" . $job['id'] . "'>Edit</a>
                        <a class='btn delete-btn' onclick=\"return confirm('Delete this job?')\" href='delete_job.php?id=" . $job['id'] . "'>Delete</a>
                      </td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='6' style='text-align:center;color:#777;'>No jobs posted yet.</td></tr>";
      }
      ?>
    </table>

    <footer>
      © <?php echo date("Y"); ?> JobPortal Cameroon — Empowering Local Opportunities 🇨🇲
    </footer>
  </div>

</body>
</html>
>>>>>>> 76038d0 (commit changes)
