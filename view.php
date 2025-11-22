<<<<<<< HEAD
<?php
session_start();
include 'DB.php';

if (isset($_GET['id'])){
    $job_id = $_GET['id'];

    $sql = "SELECT * FROM jobs WHERE id = $job_id ";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result)>0){
        $job = mysqli_fetch_assoc($result);
    }else{
        echo "job not found";
        exit;
    }
 }
?>

<!DOCTYPE html>
<html>

<head>

    <title><?php echo $job['title']; ?> - Job Details</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: "Poppins", Arial, sans-serif;
    }

    body {
        background: #f3f6f4;
        margin: 0;
        padding: 0;
    }

    /* ===== HEADER ===== */
    header {
        background: #006400; /* Green (Cameroon Flag) */
        padding: 16px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
    }

    header .logo {
        font-weight: bold;
        font-size: 22px;
        text-decoration: none;
        color: #FFD700; /* Yellow */
    }

    nav a {
        margin: 0 12px;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
    }

    nav a:hover {
        color: #FFD700;
    }

    /* ===== JOB DETAILS CONTAINER ===== */
    .container {
        background: #fff;
        padding: 25px 30px;
        border-radius: 14px;
        max-width: 750px;
        margin: 50px auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 6px solid #CE1126; /* Red accent */
    }

    h2 {
        color: #006400;
        margin-bottom: 10px;
    }

    p {
        color: #444;
        line-height: 1.7;
        font-size: 15px;
    }

    p strong {
        color: #CE1126; /* Red title highlights */
    }

    /* ===== BUTTONS ===== */
    .btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 22px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s ease-in-out;
    }

    .btn-apply {
        background: #006400;
    }
    .btn-apply:hover {
        background: #004d00;
    }

    .back-btn {
        background: #CE1126;
        margin-right: 10px;
    }
    .back-btn:hover {
        background: #a50f20;
    }

    /* ===== FOOTER ===== */
    footer {
        text-align: center;
        background: #006400;
        padding: 15px;
        margin-top: 40px;
        color: #fff;
        border-top: 3px solid #FFD700;
    }
</style>

    
</head>
<body>
<header>
  <a href="home.php" class="logo">JobPortal</a>
  <nav>
    <a href="home.php">Home</a>
    <a href="jobs.php">Jobs</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
    <h2><?php echo $job['title']; ?></h2>
    <p><strong>Company:</strong> <?php echo $job['company_name']; ?></p>
    <p><strong>Location:</strong> <?php echo $job['location']; ?></p>
    <p><strong>Employment Type:</strong> <?php echo $job['employment_type']; ?></p>
    <p><strong>Age Requirement:</strong> <?php echo $job['age_requirement']; ?></p>
    <p><strong>Gender:</strong> <?php echo $job['gender_requirement']; ?></p>
    <p><strong>Salary:</strong> <?php echo $job['salary']; ?></p>
    <p><strong>Description:</strong><br><?php echo nl2br($job['description']); ?></p>

   
</div>
<footer>
  <p>&copy; <?php echo date('Y'); ?> JobPortal - Connecting Talent with Opportunities</p>
</footer>


</body>
</html>
=======
<?php

include_once 'DB.php';

if (isset($_GET['id'])){
    $job_id = $_GET['id'];

    $sql = "SELECT * FROM jobs WHERE id = $job_id ";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result)>0){
        $job = mysqli_fetch_assoc($result);
    }else{
        echo "job not found";
        exit;
    }
 }
?>

<!DOCTYPE html>
<html>

<head>

    <title><?php echo $job['title']; ?> - Job Details</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: "Poppins", Arial, sans-serif;
    }

    body {
        background: #f3f6f4;
        margin: 0;
        padding: 0;
    }

    /* ===== HEADER ===== */
    header {
        background: #006400; /* Green (Cameroon Flag) */
        padding: 16px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
    }

    header .logo {
        font-weight: bold;
        font-size: 22px;
        text-decoration: none;
        color: #FFD700; /* Yellow */
    }

    nav a {
        margin: 0 12px;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
    }

    nav a:hover {
        color: #FFD700;
    }

    /* ===== JOB DETAILS CONTAINER ===== */
    .container {
        background: #fff;
        padding: 25px 30px;
        border-radius: 14px;
        max-width: 750px;
        margin: 50px auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 6px solid #CE1126; /* Red accent */
    }

    h2 {
        color: #006400;
        margin-bottom: 10px;
    }

    p {
        color: #444;
        line-height: 1.7;
        font-size: 15px;
    }

    p strong {
        color: #CE1126; /* Red title highlights */
    }

    /* ===== BUTTONS ===== */
    .btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 22px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s ease-in-out;
    }

    .btn-apply {
        background: #006400;
    }
    .btn-apply:hover {
        background: #004d00;
    }

    .back-btn {
        background: #CE1126;
        margin-right: 10px;
    }
    .back-btn:hover {
        background: #a50f20;
    }

    /* ===== FOOTER ===== */
    footer {
        text-align: center;
        background: #006400;
        padding: 15px;
        margin-top: 40px;
        color: #fff;
        border-top: 3px solid #FFD700;
    }
</style>

    
</head>
<body>
<header>
  <a href="home.php" class="logo">JobPortal</a>
  <nav>
    <a href="home.php">Home</a>
    <a href="jobs.php">Jobs</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
    <h2><?php echo $job['title']; ?></h2>
    <p><strong>Company:</strong> <?php echo $job['company_name']; ?></p>
    <p><strong>Location:</strong> <?php echo $job['location']; ?></p>
    <p><strong>Employment Type:</strong> <?php echo $job['employment_type']; ?></p>
    <p><strong>Age Requirement:</strong> <?php echo $job['age_requirement']; ?></p>
    <p><strong>Gender:</strong> <?php echo $job['gender_requirement']; ?></p>
    <p><strong>Salary:</strong> <?php echo $job['salary']; ?></p>
    <p><strong>Description:</strong><br><?php echo nl2br($job['description']); ?></p>

   
</div>
<footer>
  <p>&copy; <?php echo date('Y'); ?> JobPortal - Connecting Talent with Opportunities</p>
</footer>


</body>
</html>
>>>>>>> 76038d0 (commit changes)
