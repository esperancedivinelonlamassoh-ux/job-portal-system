<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "job_portal";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

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

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM jobs WHERE 1";
if ($q != '') {
    $q = $conn->real_escape_string($q);
    $sql .= " AND (title LIKE '%$q%' OR description LIKE '%$q%' OR location LIKE '%$q%')";
}
$sql .= " ORDER BY created_at DESC";
$jobs = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JobPortal Cameroon</title>

<style>
* { box-sizing: border-box; font-family: 'Poppins', sans-serif; }

body {
  margin: 0;
  background: #f7f8fa;
  color: #222;
}

/* ===== HEADER ===== */
header {
  background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 40px;
  flex-wrap: wrap;
}

/* 🔥 Glow + Pulse Logo */
.logo {
  font-size: 24px;
  font-weight: bold;
  color: white;
  text-decoration: none;
  animation: glow 2s infinite alternate;
}

@keyframes glow {
  from { text-shadow: 0 0 5px #fff; }
  to { text-shadow: 0 0 20px #fff, 0 0 30px #ffcc00; }
}

nav a {
  margin-left: 20px;
  text-decoration: none;
  color: white;
  position: relative;
}

/* underline animation */
nav a::after {
  content: "";
  width: 0;
  height: 2px;
  background: white;
  position: absolute;
  left: 0;
  bottom: -4px;
  transition: 0.3s;
}

nav a:hover::after {
  width: 100%;
}

/* ===== HERO ===== */
.hero {
  text-align: center;
  padding: 80px 20px;
  background: url('cameroon-bg.jpg') center/cover no-repeat, rgba(0,0,0,0.5);
  background-blend-mode: darken;
  color: white;
}

/* ✨ Typing effect */
.typing {
  font-size: 40px;
  border-right: 3px solid white;
  width: fit-content;
  margin: auto;
  overflow: hidden;
  white-space: nowrap;
  animation: typing 4s steps(40), blink 0.6s infinite;
}

@keyframes typing {
  from { width: 0 }
  to { width: 100% }
}

@keyframes blink {
  50% { border-color: transparent }
}

/* SEARCH */
.search-form {
  display: flex;
  justify-content: center;
  gap: 10px;
  max-width: 600px;
  margin: 25px auto;
  flex-wrap: wrap;
}

.search-form input {
  width: 70%;
  padding: 12px;
  border-radius: 6px;
  border: none;
}

.search-form button {
  padding: 12px 20px;
  background: #fcd116;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}

.search-form button:hover {
  transform: scale(1.1);
}

/* ===== JOBS ===== */
.container {
  max-width: 1100px;
  margin: 50px auto;
  padding: 0 20px;
}

.section-title {
  text-align: center;
  font-size: 26px;
  color: #007a3d;
}

/* grid */
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 25px;
}

/* ✨ animated cards */
.job-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  opacity: 0;
  transform: translateY(30px);
  animation: fadeUp 0.6s forwards;
}

.job-card:nth-child(1){animation-delay:0.1s;}
.job-card:nth-child(2){animation-delay:0.2s;}
.job-card:nth-child(3){animation-delay:0.3s;}
.job-card:nth-child(4){animation-delay:0.4s;}

@keyframes fadeUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.job-card:hover {
  transform: scale(1.03);
}

.job-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.job-card-content {
  padding: 20px;
}

.job-card h3 {
  color: #ce1126;
}

/* button */
.job-card a {
  display: inline-block;
  margin-top: 10px;
  background: #007a3d;
  color: white;
  padding: 10px 15px;
  border-radius: 6px;
  text-decoration: none;
}

.save-btn {
  margin-left: 10px;
  padding: 10px;
  border: none;
  cursor: pointer;
}

/* ===== FOOTER ===== */
footer {
  text-align: center;
  background: #003a63;
  color: white;
  padding: 25px;
  margin-top: 50px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .typing { font-size: 26px; }

  header {
    flex-direction: column;
  }

  nav a {
    margin: 10px;
  }

  .search-form {
    flex-direction: column;
  }

  .search-form input,
  .search-form button {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
</head>

<body>

<header>
  <a href="home.php" class="logo"> Cameroon JobPortal </a>
  <nav>
    <a href="home.php">Home</a>
    <a href="jobs.php">Jobs</a>
    <a href="company_reviews.php">Reviews</a>
    <a href="find_salaries.php">Salaries</a>
    <a href="login_applicant.php">Sign In</a>
  </nav>
</header>

<section class="hero">
  <div class="typing">Find Your Dream Job in Cameroon 🇨🇲</div>

  <form class="search-form" method="get">
    <input type="text" name="q" placeholder="Search jobs or cities"
      value="<?php echo htmlspecialchars($q); ?>">
    <button type="submit">Search</button>
  </form>
</section>

<div class="container">
  <h2 class="section-title">Latest Job Openings</h2>

  <div class="grid">
    <?php while ($job = $jobs->fetch_assoc()): ?>
      <div class="job-card">
        <img src="<?php echo !empty($job['image']) ? htmlspecialchars($job['image']) : 'default-job.jpg'; ?>">
        <div class="job-card-content">
          <h3><?php echo htmlspecialchars($job['title']); ?></h3>
          <div class="meta"><?php echo htmlspecialchars($job['location']); ?> — <?php echo htmlspecialchars($job['category']); ?></div>
          <p><?php echo substr(htmlspecialchars($job['description']), 0, 100) . '...'; ?></p>
          <a href="view.php?id=<?php echo $job['id']; ?>">View Job</a>
          <button class="save-btn"></button>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> JobPortal Cameroon 🇨🇲
</footer>

<script>
//  save button interaction
document.querySelectorAll(".save-btn").forEach(btn => {
  btn.onclick = function(){
    this.innerText = "Saved ";
    this.style.background = "green";
    this.style.color = "white";
  }
});
</script>

</body>
</html>