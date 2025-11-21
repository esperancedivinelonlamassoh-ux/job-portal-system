<?php
include 'DB.php';

// Handle search filters
$keyword = $_GET['keyword'] ?? '';
$location = $_GET['location'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT * FROM jobs WHERE 1=1";
if ($keyword != '') {
    $query .= " AND (title LIKE '%$keyword%' OR company_name LIKE '%$keyword%')";
}
if ($location != '') {
    $query .= " AND location LIKE '%$location%'";
}
if ($category != '') {
    $query .= " AND category LIKE '%$category%'";
}

$query .= " ORDER BY date_posted DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Jobs in Cameroon | JobConnect CM</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #222;
        }

        /* HEADER */
        header {
            background: linear-gradient(90deg, #007a33, #d21034, #ffcc00);
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
            font-weight: bold;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.7;
        }

        /* MAIN CONTAINER */
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .page-title h2 {
            color: #007a33;
            font-size: 26px;
            margin-bottom: 5px;
        }

        .page-title p {
            color: #555;
            font-size: 15px;
        }

        /* SEARCH SECTION */
        .search-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .search-form input,
        .search-form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            flex: 1 1 200px;
        }

        .search-form button {
            background: #007a33;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-form button:hover {
            background: #005f27;
        }

        /* JOB CARDS */
        .job-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .job-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border-top: 5px solid #ffcc00;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.3s;
        }

        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .job-card h3 {
            margin: 0;
            color: #d21034;
            font-size: 18px;
        }

        .meta {
            color: #555;
            font-size: 14px;
            margin: 5px 0;
        }

        .salary {
            color: #007a33;
            font-weight: bold;
        }

        .apply-btn {
            background: #d21034;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .apply-btn:hover {
            background: #a50f28;
        }

        /* FOOTER */
        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 14px;
        }

        footer span {
            color: #ffcc00;
            font-weight: bold;
        }
    </style>
</head>
<header>
    <h1>Cameroon JobPortal</h1>
    <nav>
      <a href="home.php">Home</a>
        <a href="jobs.php">Jobs</a>
        <a href="recommended_jobs.php">Recommended Jobs</a>
        <a href="applied_jobs.php">Applied Jobs</a>
        
         <a href="dashboard_applicant.php">Dashboard</a>
    </nav>
</header>
<body>


<div class="container">
    <div class="page-title">
        <h2>Find Jobs in Cameroon</h2>
        <p>Discover opportunities in Douala, Yaoundé, Buea, Bamenda, and beyond 🇨🇲</p>
    </div>

    <form class="search-form" method="GET" action="">
        <input type="text" name="keyword" placeholder="Job title or company" value="<?php echo htmlspecialchars($keyword); ?>">
        <input type="text" name="location" placeholder="e.g. Douala, Yaoundé, Limbe" value="<?php echo htmlspecialchars($location); ?>">
        <select name="category">
            <option value="">All Categories</option>
            <option value="IT" <?php if ($category=='IT') echo 'selected'; ?>>IT & Software</option>
            <option value="Accounting" <?php if ($category=='Accounting') echo 'selected'; ?>>Accounting</option>
            <option value="Marketing" <?php if ($category=='Marketing') echo 'selected'; ?>>Marketing</option>
            <option value="Engineering" <?php if ($category=='Engineering') echo 'selected'; ?>>Engineering</option>
            <option value="Education" <?php if ($category=='Education') echo 'selected'; ?>>Education</option>
            <option value="Health" <?php if ($category=='Health') echo 'selected'; ?>>Health</option>
        </select>
        <button type="submit">Search</button>
    </form>
<div class="job-list">
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($job = mysqli_fetch_assoc($result)) {
            

            echo '<div class="job-card">';
            
            echo '<h3>' . htmlspecialchars($job['title']) . '</h3>';
            echo '<div class="meta"><strong>' . htmlspecialchars($job['company_name']) . '</strong> — ' . htmlspecialchars($job['location']) . '</div>';
            echo '<div class="meta">Category: ' . htmlspecialchars($job['category'] ?? 'Not specified') . '</div>';
            echo '<div class="salary">Salary: ' . htmlspecialchars($job['salary'] ?? 'Negotiable') . '</div>';
            echo '<div class="meta">Posted on: ' . htmlspecialchars($job['date_posted']) . '</div>';
            echo '<a class="apply-btn" href="apply.php?job_id=' . urlencode($job['id']) . '">Apply Now</a>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align:center; color:#555;">No jobs found. Try another search.</p>';
    }
    ?>
</div>

</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> <span>JobConnect CM</span> — Connecting Cameroonians to Opportunities 🇨🇲</p>
</footer>

</body>
</html>
