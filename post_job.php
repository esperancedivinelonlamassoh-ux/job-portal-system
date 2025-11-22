<<<<<<< HEAD
<?php
include("DB.php");
session_start();

// Restrict to organization users only
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = DB::get();

    // Image Upload
    $image_path = null;
    if (isset($_FILES['job_image']) && $_FILES['job_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = basename($_FILES["job_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($file_type, ['jpg','jpeg','png','gif'])) {
            if (move_uploaded_file($_FILES["job_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }
    }

    // Insert Job including category + gender
    $stmt = $pdo->prepare('INSERT INTO jobs 
        (organization_id, title, company_name, slug, description, location, salary, employment_type, age_requirement, required_documents, gender_requirement, job_category, image)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );

    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $_POST['title']));

    $stmt->execute([
        $_SESSION['org_id'],
        $_POST['title'],
        $_POST['company_name'],
        $slug,
        $_POST['description'],
        $_POST['location'],
        $_POST['salary'],
        $_POST['employment_type'],
        $_POST['age_requirement'],
        $_POST['required_documents'],
        $_POST['gender_requirement'],
        $_POST['job_category'], // ✔ New field added
        $image_path
    ]);

    header('Location: admin.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Post Job - JobPortal Cameroon 🇨🇲</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
        }
        .navbar h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: 0.3s;
        }
        .navbar a:hover { opacity: 0.7; }

        /* FORM CARD */
        form {
            width: 80%;
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
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
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 600;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            transition: 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--cam-green);
            box-shadow: 0 0 5px rgba(0, 122, 61, 0.4);
        }

        /* BUTTON */
        button {
            width: 100%;
            background: var(--cam-green);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #005a45;
        }

        /* FOOTER */
        footer {
            margin-top: 40px;
            padding: 18px;
            text-align: center;
            background: var(--cam-red);
            color: white;
            font-size: 14px;
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
        <a href="admin.php">Dashboard</a>
        <a href="post_job.php"><strong>Post Job</strong></a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<form method="post" enctype="multipart/form-data">
    <h2>Post a New Job Opportunity 🇨🇲</h2>

    <label>Job Image:</label>
    <input type="file" name="job_image" accept="image/*">

    <label>Job Title:</label>
    <input name="title" required placeholder="e.g., Accountant, Driver">

    <label>Company Name:</label>
    <input name="company_name" required placeholder="e.g., MTN Cameroon">

    <label>Location:</label>
    <input name="location" placeholder="e.g., Douala, Yaoundé, Buea">

    <label>Salary (FCFA):</label>
    <input name="salary" placeholder="e.g., 100,000 - 250,000 FCFA">

    <label>Age Requirement:</label>
    <input name="age_requirement" placeholder="e.g., 20 - 35 years">

    <!-- CATEGORY FIELD -->
    <label>Job Category:</label>
    <select name="job_category" required>
        <option value="">-- Select Category --</option>
        <option value="Accounting">Accounting</option>
        <option value="Driving">Driving</option>
        <option value="Engineering">Engineering</option>
        <option value="IT & Software">IT & Software</option>
        <option value="Education">Education</option>
        <option value="Healthcare">Healthcare</option>
        <option value="Security">Security</option>
        <option value="Construction">Construction</option>
        <option value="Agriculture">Agriculture</option>
        <option value="Hospitality">Hospitality</option>
        <option value="Others">Others</option>
    </select>

    <!-- GENDER FIELD -->
    <label>Gender Requirement:</label>
    <select name="gender_requirement">
        <option value="None">No Preference</option>
        <option value="Male">Male Only</option>
        <option value="Female">Female Only</option>
        <option value="Both">Male & Female</option>
    </select>

    <label>Employment Type:</label>
    <select name="employment_type">
        <option value="full_time">Full Time</option>
        <option value="part_time">Part Time</option>
        <option value="contract">Contract</option>
        <option value="internship">Internship</option>
        <option value="remote">Remote</option>
    </select>

    <label>Required Documents:</label>
    <input name="required_documents" placeholder="e.g., CV, ID Card, Letter">

    <label>Job Description:</label>
    <textarea name="description" rows="7" placeholder="Describe responsibilities, skills..."></textarea>

    <button type="submit">Publish Job 🇨🇲</button>
</form>

<footer>
    © <?php echo date('Y'); ?> <span>JobPortal Cameroon</span> — Empowering Youth & Opportunities 🇨🇲
</footer>

</body>
</html>
=======
<?php
include_once("DB.php");


// Restrict to organization users only
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = DB::get();

    // Image Upload
    $image_path = null;
    if (isset($_FILES['job_image']) && $_FILES['job_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = basename($_FILES["job_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($file_type, ['jpg','jpeg','png','gif'])) {
            if (move_uploaded_file($_FILES["job_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }
    }

    // Insert Job including category + gender
    $stmt = $pdo->prepare('INSERT INTO jobs 
        (organization_id, title, company_name, slug, description, location, salary, employment_type, age_requirement, required_documents, gender_requirement, job_category, image)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );

    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $_POST['title']));

    $stmt->execute([
        $_SESSION['org_id'],
        $_POST['title'],
        $_POST['company_name'],
        $slug,
        $_POST['description'],
        $_POST['location'],
        $_POST['salary'],
        $_POST['employment_type'],
        $_POST['age_requirement'],
        $_POST['required_documents'],
        $_POST['gender_requirement'],
        $_POST['job_category'], // ✔ New field added
        $image_path
    ]);

    header('Location: admin.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Post Job - JobPortal Cameroon 🇨🇲</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
        }
        .navbar h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: 0.3s;
        }
        .navbar a:hover { opacity: 0.7; }

        /* FORM CARD */
        form {
            width: 80%;
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
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
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 600;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            transition: 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--cam-green);
            box-shadow: 0 0 5px rgba(0, 122, 61, 0.4);
        }

        /* BUTTON */
        button {
            width: 100%;
            background: var(--cam-green);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #005a45;
        }

        /* FOOTER */
        footer {
            margin-top: 40px;
            padding: 18px;
            text-align: center;
            background: var(--cam-red);
            color: white;
            font-size: 14px;
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
        <a href="admin.php">Dashboard</a>
        <a href="post_job.php"><strong>Post Job</strong></a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<form method="post" enctype="multipart/form-data">
    <h2>Post a New Job Opportunity 🇨🇲</h2>

    <label>Job Image:</label>
    <input type="file" name="job_image" accept="image/*">

    <label>Job Title:</label>
    <input name="title" required placeholder="e.g., Accountant, Driver">

    <label>Company Name:</label>
    <input name="company_name" required placeholder="e.g., MTN Cameroon">

    <label>Location:</label>
    <input name="location" placeholder="e.g., Douala, Yaoundé, Buea">

    <label>Salary (FCFA):</label>
    <input name="salary" placeholder="e.g., 100,000 - 250,000 FCFA">

    <label>Age Requirement:</label>
    <input name="age_requirement" placeholder="e.g., 20 - 35 years">

    <!-- CATEGORY FIELD -->
    <label>Job Category:</label>
    <select name="job_category" required>
        <option value="">-- Select Category --</option>
        <option value="Accounting">Accounting</option>
        <option value="Driving">Driving</option>
        <option value="Engineering">Engineering</option>
        <option value="IT & Software">IT & Software</option>
        <option value="Education">Education</option>
        <option value="Healthcare">Healthcare</option>
        <option value="Security">Security</option>
        <option value="Construction">Construction</option>
        <option value="Agriculture">Agriculture</option>
        <option value="Hospitality">Hospitality</option>
        <option value="Others">Others</option>
    </select>

    <!-- GENDER FIELD -->
    <label>Gender Requirement:</label>
    <select name="gender_requirement">
        <option value="None">No Preference</option>
        <option value="Male">Male Only</option>
        <option value="Female">Female Only</option>
        <option value="Both">Male & Female</option>
    </select>

    <label>Employment Type:</label>
    <select name="employment_type">
        <option value="full_time">Full Time</option>
        <option value="part_time">Part Time</option>
        <option value="contract">Contract</option>
        <option value="internship">Internship</option>
        <option value="remote">Remote</option>
    </select>

    <label>Required Documents:</label>
    <input name="required_documents" placeholder="e.g., CV, ID Card, Letter">

    <label>Job Description:</label>
    <textarea name="description" rows="7" placeholder="Describe responsibilities, skills..."></textarea>

    <button type="submit">Publish Job 🇨🇲</button>
</form>

<footer>
    © <?php echo date('Y'); ?> <span>JobPortal Cameroon</span> — Empowering Youth & Opportunities 🇨🇲
</footer>

</body>
</html>
>>>>>>> 76038d0 (commit changes)
