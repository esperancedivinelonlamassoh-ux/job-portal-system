<<<<<<< HEAD
<?php
include('DB.php');
$pdo = DB::get();

// Create table if not exists
$pdo->exec("
CREATE TABLE IF NOT EXISTS salaries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  job_title VARCHAR(255) NOT NULL,
  company_name VARCHAR(255) NOT NULL,
  salary DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;
");

// Handle salary submission (if you want users to add salaries)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job = trim($_POST['job_title'] ?? '');
    $company = trim($_POST['company_name'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);

    if ($job !== '' && $company !== '' && $salary > 0) {
        $stmt = $pdo->prepare("INSERT INTO salaries (job_title, company_name, salary) VALUES (?, ?, ?)");
        $stmt->execute([$job, $company, $salary]);
        $success = "✅ Salary information submitted!";
    } else {
        $error = "⚠️ Please fill all fields correctly.";
    }
}

// Fetch salaries
$searchJob = trim($_GET['job_title'] ?? '');
$searchCompany = trim($_GET['company_name'] ?? '');

$query = "SELECT * FROM salaries WHERE 1=1";
$params = [];

if ($searchJob !== '') {
    $query .= " AND job_title LIKE ?";
    $params[] = "%$searchJob%";
}
if ($searchCompany !== '') {
    $query .= " AND company_name LIKE ?";
    $params[] = "%$searchCompany%";
}

$query .= " ORDER BY created_at DESC LIMIT 20";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$salaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Salaries - Job Portal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
body { margin: 0; background-color: #f8faff; font-family: 'Segoe UI', sans-serif; }
header {
  background-color: #004aad;
  color: white;
  padding: 15px 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.logo { font-size: 24px; font-weight: bold; color: white; text-decoration: none; }
nav a { margin-left: 25px; text-decoration: none; color: white; font-weight: 500; transition: color 0.3s; }
nav a:hover { color: #ffde59; }
footer {
  background-color: #003580;
  color: white;
  text-align: center;
  padding: 25px 10px;
  margin-top: 50px;
}
.hero {
  background: linear-gradient(135deg, #004aad, #003580);
  color: white;
  padding: 60px 20px;
  text-align: center;
  border-radius: 0 0 40px 40px;
}
.hero h1 { font-weight: 700; font-size: 38px; margin-bottom: 10px; }
.hero p { font-size: 17px; opacity: 0.9; }
.salary-section {
  max-width: 1100px;
  margin: -30px auto 60px auto;
  background: #fff;
  border-radius: 15px;
  padding: 40px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.salary-form h3 { color: #004aad; font-weight: 600; margin-bottom: 20px; }
.salary-form label { font-weight: 600; color: #333; margin-top: 10px; }
.salary-form input, .salary-form select {
  border: 2px solid #dce3f7;
  border-radius: 8px;
  padding: 10px;
  width: 100%;
}
.salary-form button {
  background-color: #004aad;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: bold;
  margin-top: 15px;
  width: 100%;
  transition: background 0.3s;
}
.salary-form button:hover { background-color: #003580; }

.salary-list {
  margin-top: 40px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 20px;
}
.salary-card {
  background: #fff;
  border: 1px solid #e3e8f0;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.salary-card h4 {
  font-size: 18px;
  font-weight: bold;
  color: #004aad;
}
.salary-card p { color: #555; font-size: 15px; margin-bottom: 10px; }
.salary-date { font-size: 13px; color: #777; text-align: right; }
.alert-success, .alert-danger {
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-weight: 500;
}
.alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
.alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
</style>
</head>
<body>

<header>
  <a href="home.php" class="logo">JobPortal</a>
  <nav>
    <a href="home.php">Home</a>
    <a href="company_reviews.php">Company Reviews</a>
    <a href="find_salaries.php" class="active">Find Salaries</a>
    <a href="jobs.php">Jobs</a>
    <a href="login_applicant.php">Login</a>
  </nav>
</header>

<div class="hero">
  <h1>Find Salaries</h1>
  <p>Discover salaries for various job titles and companies in Cameroon.</p>
</div>

<div class="salary-section">
  <div class="salary-form">
    <h3>Search for Salaries</h3>
    <?php if (!empty($error)): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="GET">
      <label>Job Title</label>
      <input type="text" name="job_title" placeholder="e.g. Software Engineer" value="<?= htmlspecialchars($searchJob) ?>">

      <label>Company Name</label>
      <input type="text" name="company_name" placeholder="e.g. MTN Cameroon" value="<?= htmlspecialchars($searchCompany) ?>">

      <button type="submit">Search Salaries</button>
    </form>
  </div>

  <hr style="margin: 40px 0;">

  <h3 style="color:#004aad;">Recent Salary Entries</h3>
  <div class="salary-list">
    <?php if (count($salaries) === 0): ?>
      <p>No salary data found.</p>
    <?php endif; ?>

    <?php foreach ($salaries as $s): ?>
      <div class="salary-card">
        <h4><?= htmlspecialchars($s['job_title']) ?> @ <?= htmlspecialchars($s['company_name']) ?></h4>
        <p>Salary: <strong><?= number_format($s['salary'], 2) ?> XAF</strong></p>
        <div class="salary-date"><?= date('M j, Y', strtotime($s['created_at'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<footer>
  <p>&copy; <?= date('Y') ?> JobPortal — Empowering Careers Everywhere</p>
</footer>

</body>
</html>
=======
<?php
include_once('DB.php');
$pdo = DB::get();

// Create table if not exists
$pdo->exec("
CREATE TABLE IF NOT EXISTS salaries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  job_title VARCHAR(255) NOT NULL,
  company_name VARCHAR(255) NOT NULL,
  salary DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;
");

// Handle salary submission (if you want users to add salaries)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job = trim($_POST['job_title'] ?? '');
    $company = trim($_POST['company_name'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);

    if ($job !== '' && $company !== '' && $salary > 0) {
        $stmt = $pdo->prepare("INSERT INTO salaries (job_title, company_name, salary) VALUES (?, ?, ?)");
        $stmt->execute([$job, $company, $salary]);
        $success = "✅ Salary information submitted!";
    } else {
        $error = "⚠️ Please fill all fields correctly.";
    }
}

// Fetch salaries
$searchJob = trim($_GET['job_title'] ?? '');
$searchCompany = trim($_GET['company_name'] ?? '');

$query = "SELECT * FROM salaries WHERE 1=1";
$params = [];

if ($searchJob !== '') {
    $query .= " AND job_title LIKE ?";
    $params[] = "%$searchJob%";
}
if ($searchCompany !== '') {
    $query .= " AND company_name LIKE ?";
    $params[] = "%$searchCompany%";
}

$query .= " ORDER BY created_at DESC LIMIT 20";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$salaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Salaries - Job Portal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
body { margin: 0; background-color: #f8faff; font-family: 'Segoe UI', sans-serif; }
header {
  background-color: #004aad;
  color: white;
  padding: 15px 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.logo { font-size: 24px; font-weight: bold; color: white; text-decoration: none; }
nav a { margin-left: 25px; text-decoration: none; color: white; font-weight: 500; transition: color 0.3s; }
nav a:hover { color: #ffde59; }
footer {
  background-color: #003580;
  color: white;
  text-align: center;
  padding: 25px 10px;
  margin-top: 50px;
}
.hero {
  background: linear-gradient(135deg, #004aad, #003580);
  color: white;
  padding: 60px 20px;
  text-align: center;
  border-radius: 0 0 40px 40px;
}
.hero h1 { font-weight: 700; font-size: 38px; margin-bottom: 10px; }
.hero p { font-size: 17px; opacity: 0.9; }
.salary-section {
  max-width: 1100px;
  margin: -30px auto 60px auto;
  background: #fff;
  border-radius: 15px;
  padding: 40px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.salary-form h3 { color: #004aad; font-weight: 600; margin-bottom: 20px; }
.salary-form label { font-weight: 600; color: #333; margin-top: 10px; }
.salary-form input, .salary-form select {
  border: 2px solid #dce3f7;
  border-radius: 8px;
  padding: 10px;
  width: 100%;
}
.salary-form button {
  background-color: #004aad;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: bold;
  margin-top: 15px;
  width: 100%;
  transition: background 0.3s;
}
.salary-form button:hover { background-color: #003580; }

.salary-list {
  margin-top: 40px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 20px;
}
.salary-card {
  background: #fff;
  border: 1px solid #e3e8f0;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.salary-card h4 {
  font-size: 18px;
  font-weight: bold;
  color: #004aad;
}
.salary-card p { color: #555; font-size: 15px; margin-bottom: 10px; }
.salary-date { font-size: 13px; color: #777; text-align: right; }
.alert-success, .alert-danger {
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-weight: 500;
}
.alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
.alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
</style>
</head>
<body>

<header>
  <a href="home.php" class="logo">JobPortal</a>
  <nav>
    <a href="home.php">Home</a>
    <a href="company_reviews.php">Company Reviews</a>
    <a href="find_salaries.php" class="active">Find Salaries</a>
    <a href="jobs.php">Jobs</a>
    <a href="login_applicant.php">Login</a>
  </nav>
</header>

<div class="hero">
  <h1>Find Salaries</h1>
  <p>Discover salaries for various job titles and companies in Cameroon.</p>
</div>

<div class="salary-section">
  <div class="salary-form">
    <h3>Search for Salaries</h3>
    <?php if (!empty($error)): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="GET">
      <label>Job Title</label>
      <input type="text" name="job_title" placeholder="e.g. Software Engineer" value="<?= htmlspecialchars($searchJob) ?>">

      <label>Company Name</label>
      <input type="text" name="company_name" placeholder="e.g. MTN Cameroon" value="<?= htmlspecialchars($searchCompany) ?>">

      <button type="submit">Search Salaries</button>
    </form>
  </div>

  <hr style="margin: 40px 0;">

  <h3 style="color:#004aad;">Recent Salary Entries</h3>
  <div class="salary-list">
    <?php if (count($salaries) === 0): ?>
      <p>No salary data found.</p>
    <?php endif; ?>

    <?php foreach ($salaries as $s): ?>
      <div class="salary-card">
        <h4><?= htmlspecialchars($s['job_title']) ?> @ <?= htmlspecialchars($s['company_name']) ?></h4>
        <p>Salary: <strong><?= number_format($s['salary'], 2) ?> XAF</strong></p>
        <div class="salary-date"><?= date('M j, Y', strtotime($s['created_at'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<footer>
  <p>&copy; <?= date('Y') ?> JobPortal — Empowering Careers Everywhere</p>
</footer>

</body>
</html>
>>>>>>> 76038d0 (commit changes)
