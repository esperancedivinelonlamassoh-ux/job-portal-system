<?php
// ===== DATABASE CONNECTION =====
$host = "localhost";
$user = "root";
$pass = "";
$db   = "job_portal";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// ===== CREATE TABLES AND SAMPLE DATA =====
$conn->query("CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    location VARCHAR(100),
    category VARCHAR(100),
    salary VARCHAR(100),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ===== HANDLE JOB UPLOAD (Admin use only) =====
if (isset($_POST['add_job'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $location = $conn->real_escape_string($_POST['location']);
    $category = $conn->real_escape_string($_POST['category']);
    $salary = $conn->real_escape_string($_POST['salary']);

    // Handle image upload
    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

    $sql = "INSERT INTO jobs (title, description, location, category, salary, image)
            VALUES ('$title', '$description', '$location', '$category', '$salary', '$imagePath')";
    $conn->query($sql);
}

// ===== SEARCH FUNCTIONALITY =====
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM jobs WHERE 1";
if ($q != '') {
    $sql .= " AND (title LIKE '%$q%' OR description LIKE '%$q%' OR location LIKE '%$q%')";
}
$sql .= " ORDER BY created_at DESC";
$jobs = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JobPortal Cameroon - Find Great Places to Work</title>
  <style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { margin: 0; background: #f7f8fa; color: #222; }

    /* ===== HEADER ===== */
    header {
      background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
      display: flex; justify-content: space-between; align-items: center;
      padding: 15px 60px; color: white;
    }
    .logo {
      font-size: 24px; font-weight: bold; color: white;
      text-decoration: none;
      letter-spacing: 1px;
    }
    nav a {
      margin-left: 25px;
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: color 0.3s;
    }
    nav a:hover {
      color: #fcd116;
    }

    /* ===== HERO SECTION ===== */
    .hero {
      text-align: center;
      padding: 70px 20px;
      background: url('cameroon-bg.jpg') center/cover no-repeat, rgba(0,0,0,0.4);
      background-blend-mode: darken;
      color: white;
    }
    .hero h1 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 10px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .hero p {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .search-form {
      display: flex; justify-content: center; align-items: center; gap: 10px;
      max-width: 600px; margin: 0 auto;
    }
    .search-form input {
      width: 70%; padding: 12px; border-radius: 5px; border: 1px solid #ccc;
    }
    .search-form button {
      padding: 12px 20px;
      background: #fcd116; color: #222; font-weight: bold;
      border: none; border-radius: 5px; cursor: pointer;
    }
    .search-form button:hover {
      background: #e5bc10;
    }

    /* ===== JOBS SECTION ===== */
    .container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }
    .section-title {
      text-align: center; font-size: 26px; font-weight: 700;
      margin-bottom: 25px; color: #007a3d;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
    }
    .job-card {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .job-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }
    .job-card img {
      width: 100%; height: 180px; object-fit: cover;
    }
    .job-card-content {
      padding: 20px;
    }
    .job-card h3 {
      color: #ce1126; margin-bottom: 8px; font-size: 20px;
    }
    .meta {
      font-size: 14px; color: #555; margin-bottom: 10px;
    }
    .job-card p {
      color: #333; font-size: 14px; line-height: 1.5; margin-bottom: 10px;
    }
    .job-card a {
      display: inline-block;
      background: #007a3d; color: #fff;
      padding: 10px 15px; border-radius: 6px;
      text-decoration: none; font-weight: bold;
    }
    .job-card a:hover {
      background: #005f2f;
    }

    /* ===== FOOTER ===== */
    footer {
      text-align: center;
      background: #003a63;
      color: white;
      padding: 25px;
      font-size: 14px;
      margin-top: 50px;
    }
  </style>
</head>
<body>

<header>
  <a href="home.php" class="logo"> Cameroon JobPortal</a>
  <nav>
    <a href="home.php">Home</a>
    <a href="jobs.php">Jobs</a>
    <a href="company_reviews.php">Company Reviews</a>
    <a href="find_salaries.php">Find Salaries</a>
    <a href="login_applicant.php" style="font-weight:bold;">Sign In</a>
  </nav>
</header>

<section class="hero">
  <h1>Find Great Opportunities in Cameroon</h1>
  <p>Discover top employers and job offers across Cameroon’s leading industries</p>
  <form class="search-form" method="get">
    <input type="text" name="q" placeholder="Search by job title or city" value="<?php echo htmlspecialchars($q); ?>">
    <button type="submit">Search</button>
  </form>
</section>

<div class="container">
  <h2 class="section-title">Latest Job Openings</h2>
  <div class="grid">
    <?php while ($job = $jobs->fetch_assoc()): ?>
      <div class="job-card">
        <?php if (!empty($job['image'])): ?>
          <img src="<?php echo htmlspecialchars($job['image']); ?>" alt="Job Image">
        <?php else: ?>
          <img src="default-job.jpg" alt="Default Image">
        <?php endif; ?>
        <div class="job-card-content">
          <h3><?php echo htmlspecialchars($job['title']); ?></h3>
          <div class="meta"><?php echo htmlspecialchars($job['location']); ?> — <?php echo htmlspecialchars($job['category']); ?></div>
          <p><?php echo substr(htmlspecialchars($job['description']), 0, 100) . '...'; ?></p>
          <a href="view.php?id=<?php echo $job['id']; ?>">View Job</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> JobPortal Cameroon — Empowering the Cameroonian Workforce 🇨🇲
</footer>

</body>
</html>
