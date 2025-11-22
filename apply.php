<<<<<<< HEAD
<?php
session_start();
include 'DB.php';

// Check login
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>
        alert('Please log in first to apply for a job.');
        window.location.href='login_applicant.php';
    </script>";
    exit;
}

$applicant_id = $_SESSION['applicant_id'];
$job_id = $_GET['job_id'] ?? 0;

if ($job_id == 0) {
    die("Invalid job selection.");
}

// Fetch job details
$job_query = "SELECT title FROM jobs WHERE id = '$job_id' LIMIT 1";
$job_result = mysqli_query($conn, $job_query);

if (!$job_result || mysqli_num_rows($job_result) == 0) {
    die("Job not found.");
}

$job = mysqli_fetch_assoc($job_result);
$job_title = $job['title'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);

    if (empty($name) || empty($email) || empty($phone)) {
        die("All fields are required.");
    }

    // Uploads
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $cv_path = '';
    $id_card_path = '';

    if (!empty($_FILES['cv']['name'])) {
        $cv_name = time() . "_" . basename($_FILES["cv"]["name"]);
        $cv_target = $upload_dir . $cv_name;
        move_uploaded_file($_FILES["cv"]["tmp_name"], $cv_target);
        $cv_path = $cv_target;
    }

    if (!empty($_FILES['id_card']['name'])) {
        $id_name = time() . "_" . basename($_FILES["id_card"]["name"]);
        $id_target = $upload_dir . $id_name;
        move_uploaded_file($_FILES["id_card"]["tmp_name"], $id_target);
        $id_card_path = $id_target;
    }

    // Insert
    $query = "INSERT INTO applications 
            (job_id, applicant_id, applicant_name, applicant_email, phone, cover_letter, cv, id_card)
            VALUES ('$job_id', '$applicant_id', '$name', '$email', '$phone', '$cover_letter', '$cv_path', '$id_card_path')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Application submitted successfully!');
            window.location.href='jobs.php';
        </script>";
    } else {
        echo "<script>
            alert('Error submitting application: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Apply for Job - Cameroon Job Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        margin: 0;
        background: #f4f5f7;
    }

    :root {
        --cam-green: #007A5E;
        --cam-red: #CE1126;
        --cam-yellow: #FCD116;
    }

    /* NAVBAR */
    .navbar {
        background: linear-gradient(90deg, var(--cam-green), var(--cam-red), var(--cam-yellow));
        padding: 15px 40px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.2);
    }
    .navbar h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }
    .navbar a {
        color: white;
        margin-left: 20px;
        text-decoration: none;
        font-weight: 600;
    }

    /* FORM */
    .form-card {
        width: 85%;
        max-width: 800px;
        background: white;
        margin: 40px auto;
        padding: 35px;
        border-radius: 12px;
        border-top: 6px solid var(--cam-yellow);
        border-bottom: 4px solid var(--cam-green);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        text-align: center;
        color: var(--cam-green);
        margin-bottom: 20px;
        font-size: 26px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    input, textarea {
        width: 100%;
        padding: 13px;
        margin-bottom: 18px;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: 0.3s;
    }

    input:focus, textarea:focus {
        border-color: var(--cam-green);
        box-shadow: 0 0 5px rgba(0,122,61,0.4);
    }

    button {
        width: 100%;
        padding: 14px;
        background: var(--cam-green);
        color: white;
        font-size: 17px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background: #005a45;
    }

    footer {
        margin-top: 40px;
        padding: 16px;
        text-align: center;
        background: var(--cam-red);
        color: white;
        border-top: 3px solid var(--cam-yellow);
    }
    footer span {
        color: var(--cam-yellow);
        font-weight: bold;
    }

</style>
</head>

<body>

<div class="navbar">
    <h1>🇨🇲 JobPortal Cameroon</h1>
    <div>
        <a href="jobs.php"><strong>Jobs</strong></a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="form-card">
    <h2>Apply for: <?php echo htmlspecialchars($job_title); ?> 🇨🇲</h2>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">

        <label>Full Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Cover Letter:</label>
        <textarea name="cover_letter" rows="6" required></textarea>

        <label>Upload CV (PDF):</label>
        <input type="file" name="cv" required accept=".pdf">

        <label>Upload ID Card (JPG/PNG):</label>
        <input type="file" name="id_card" required accept=".jpg,.jpeg,.png">

        <button type="submit">Submit Application 🇨🇲</button>
    </form>
</div>

<footer>
    © <?php echo date('Y'); ?> <span>JobPortal Cameroon</span> — Empowering Youth 🇨🇲
</footer>

</body>
</html>
=======
<?php

include_once 'DB.php';

// Check login
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>
        alert('Please log in first to apply for a job.');
        window.location.href='login_applicant.php';
    </script>";
    exit;
}

$applicant_id = $_SESSION['applicant_id'];
$job_id = $_GET['job_id'] ?? 0;

if ($job_id == 0) {
    die("Invalid job selection.");
}

// Fetch job details
$job_query = "SELECT title FROM jobs WHERE id = '$job_id' LIMIT 1";
$job_result = mysqli_query($conn, $job_query);

if (!$job_result || mysqli_num_rows($job_result) == 0) {
    die("Job not found.");
}

$job = mysqli_fetch_assoc($job_result);
$job_title = $job['title'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);

    if (empty($name) || empty($email) || empty($phone)) {
        die("All fields are required.");
    }

    // Uploads
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $cv_path = '';
    $id_card_path = '';

    if (!empty($_FILES['cv']['name'])) {
        $cv_name = time() . "_" . basename($_FILES["cv"]["name"]);
        $cv_target = $upload_dir . $cv_name;
        move_uploaded_file($_FILES["cv"]["tmp_name"], $cv_target);
        $cv_path = $cv_target;
    }

    if (!empty($_FILES['id_card']['name'])) {
        $id_name = time() . "_" . basename($_FILES["id_card"]["name"]);
        $id_target = $upload_dir . $id_name;
        move_uploaded_file($_FILES["id_card"]["tmp_name"], $id_target);
        $id_card_path = $id_target;
    }

    // Insert
    $query = "INSERT INTO applications 
            (job_id, applicant_id, applicant_name, applicant_email, phone, cover_letter, cv, id_card)
            VALUES ('$job_id', '$applicant_id', '$name', '$email', '$phone', '$cover_letter', '$cv_path', '$id_card_path')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Application submitted successfully!');
            window.location.href='jobs.php';
        </script>";
    } else {
        echo "<script>
            alert('Error submitting application: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Apply for Job - Cameroon Job Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        margin: 0;
        background: #f4f5f7;
    }

    :root {
        --cam-green: #007A5E;
        --cam-red: #CE1126;
        --cam-yellow: #FCD116;
    }

    /* NAVBAR */
    .navbar {
        background: linear-gradient(90deg, var(--cam-green), var(--cam-red), var(--cam-yellow));
        padding: 15px 40px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.2);
    }
    .navbar h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }
    .navbar a {
        color: white;
        margin-left: 20px;
        text-decoration: none;
        font-weight: 600;
    }

    /* FORM */
    .form-card {
        width: 85%;
        max-width: 800px;
        background: white;
        margin: 40px auto;
        padding: 35px;
        border-radius: 12px;
        border-top: 6px solid var(--cam-yellow);
        border-bottom: 4px solid var(--cam-green);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        text-align: center;
        color: var(--cam-green);
        margin-bottom: 20px;
        font-size: 26px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    input, textarea {
        width: 100%;
        padding: 13px;
        margin-bottom: 18px;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: 0.3s;
    }

    input:focus, textarea:focus {
        border-color: var(--cam-green);
        box-shadow: 0 0 5px rgba(0,122,61,0.4);
    }

    button {
        width: 100%;
        padding: 14px;
        background: var(--cam-green);
        color: white;
        font-size: 17px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background: #005a45;
    }

    footer {
        margin-top: 40px;
        padding: 16px;
        text-align: center;
        background: var(--cam-red);
        color: white;
        border-top: 3px solid var(--cam-yellow);
    }
    footer span {
        color: var(--cam-yellow);
        font-weight: bold;
    }

</style>
</head>

<body>

<div class="navbar">
    <h1>🇨🇲 JobPortal Cameroon</h1>
    <div>
        <a href="jobs.php"><strong>Jobs</strong></a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="form-card">
    <h2>Apply for: <?php echo htmlspecialchars($job_title); ?> 🇨🇲</h2>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">

        <label>Full Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Cover Letter:</label>
        <textarea name="cover_letter" rows="6" required></textarea>

        <label>Upload CV (PDF):</label>
        <input type="file" name="cv" required accept=".pdf">

        <label>Upload ID Card (JPG/PNG):</label>
        <input type="file" name="id_card" required accept=".jpg,.jpeg,.png">

        <button type="submit">Submit Application 🇨🇲</button>
    </form>
</div>

<footer>
    © <?php echo date('Y'); ?> <span>JobPortal Cameroon</span> — Empowering Youth 🇨🇲
</footer>

</body>
</html>
>>>>>>> 76038d0 (commit changes)
