
<?php
include('DB.php');
$pdo = DB::get();

// Create table if not exists
$pdo->exec("
CREATE TABLE IF NOT EXISTS company_reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(255) NOT NULL,
  rating TINYINT NOT NULL,
  review TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;
");

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = trim($_POST['company_name'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $review = trim($_POST['review'] ?? '');

    if ($company !== '' && $rating > 0 && $review !== '') {
        $stmt = $pdo->prepare("INSERT INTO company_reviews (company_name, rating, review) VALUES (?, ?, ?)");
        $stmt->execute([$company, $rating, $review]);
        $success = "✅ Your review has been submitted!";
    } else {
        $error = "⚠️ Please fill out all fields before submitting.";
    }
}

// Fetch reviews
$stmt = $pdo->query("SELECT * FROM company_reviews ORDER BY created_at DESC LIMIT 20");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Reviews - Job Portal</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    /* ===== GENERAL LAYOUT ===== */
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
    nav a {
      margin-left: 25px;
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: color 0.3s;
    }
    nav a:hover { color: #ffde59; }

    footer {
      background-color: #003580;
      color: white;
      text-align: center;
      padding: 25px 10px;
      margin-top: 50px;
    }

    /* ===== HERO SECTION ===== */
    .hero {
      background: linear-gradient(135deg, #004aad, #003580);
      color: white;
      padding: 60px 20px;
      text-align: center;
      border-radius: 0 0 40px 40px;
    }
    .hero h1 { font-weight: 700; font-size: 38px; margin-bottom: 10px; }
    .hero p { font-size: 17px; opacity: 0.9; }

    /* ===== REVIEW SECTION ===== */
    .review-section {
      max-width: 1100px;
      margin: -30px auto 60px auto;
      background: #fff;
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    .review-form h3 { color: #004aad; font-weight: 600; margin-bottom: 20px; }
    .review-form label { font-weight: 600; color: #333; margin-top: 10px; }
    .review-form input, .review-form select, .review-form textarea {
      border: 2px solid #dce3f7;
      border-radius: 8px;
      padding: 10px;
      width: 100%;
    }
    .review-form button {
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
    .review-form button:hover { background-color: #003580; }

    .reviews-list {
      margin-top: 40px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
    }
    .review-card {
      background: #fff;
      border: 1px solid #e3e8f0;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .review-card h4 {
      font-size: 18px;
      font-weight: bold;
      color: #004aad;
    }
    .review-card .rating {
      color: #f4c430;
      margin-bottom: 8px;
      font-size: 18px;
    }
    .review-card p {
      color: #555;
      font-size: 15px;
      margin-bottom: 10px;
    }
    .review-date {
      font-size: 13px;
      color: #777;
      text-align: right;
    }

    /* ===== ALERTS ===== */
    .alert-success, .alert-danger {
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-weight: 500;
    }
    .alert-success {
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
    }
    .alert-danger {
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
      color: #721c24;
    }
  </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header>
  <a href="home.php" class="logo">JobPortal</a>
  <nav>
    <a href="home.php">Home</a>
    <a href="company_review.php" class="active">Company Reviews</a>
    <a href="find_salaries.php">Find Salaries</a>
    <a href="jobs.php">Jobs</a>
    <a href="login_applicant.php">Login</a>
  </nav>
</header>

<!-- ===== HERO ===== -->
<div class="hero">
  <h1>Company Reviews</h1>
  <p>See what others say about their workplaces — or share your experience to help others.</p>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="review-section">
  <div class="review-form">
    <h3>Write a Company Review</h3>
    <?php if (!empty($error)): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Company Name</label>
      <input type="text" name="company_name" placeholder="e.g. MTN Cameroon" required>

      <label>Rating</label>
      <select name="rating" required>
        <option value="">Select rating</option>
        <option value="5">★★★★★ - Excellent</option>
        <option value="4">★★★★☆ - Very Good</option>
        <option value="3">★★★☆☆ - Good</option>
        <option value="2">★★☆☆☆ - Fair</option>
        <option value="1">★☆☆☆☆ - Poor</option>
      </select>

      <label>Your Review</label>
      <textarea name="review" rows="5" placeholder="Share your experience..." required></textarea>

      <button type="submit">Submit Review</button>
    </form>
  </div>

  <hr style="margin: 40px 0;">

  <h3 style="color:#004aad;">Recent Reviews</h3>
  <div class="reviews-list">
    <?php if (count($reviews) === 0): ?>
      <p>No reviews yet. Be the first to share your experience!</p>
    <?php endif; ?>

    <?php foreach ($reviews as $r): ?>
      <div class="review-card">
        <h4><?= htmlspecialchars($r['company_name']) ?></h4>
        <div class="rating"><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']); ?></div>
        <p><?= nl2br(htmlspecialchars($r['review'])) ?></p>
        <div class="review-date"><?= date('M j, Y', strtotime($r['created_at'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<footer>
  <p>&copy; <?= date('Y') ?> JobPortal — Empowering Careers Everywhere</p>
</footer>

</body>
</html>
