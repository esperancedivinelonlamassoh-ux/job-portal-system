
<?php
include("DB.php");
session_start();

// Only org users can access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$pdo = DB::get();
$org_id = $_SESSION['org_id'];

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$job_id = $_GET['id'];

// Fetch job details (PDO)
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ? AND organization_id = ?");
$stmt->execute([$job_id, $org_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Job not found or you don't have permission to edit this job.";
    exit;
}

// Update job
if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $salary = trim($_POST['salary']);
    $description = trim($_POST['description']);

    // Default current image
    $image_file = $job['image'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "uploads/";
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_file = $filename;  // Save only filename, not full path
        }
    }

    // Update using PDO
    $update = $pdo->prepare("UPDATE jobs 
        SET title=?, location=?, salary=?, description=?, image=? 
        WHERE id=? AND organization_id=?");

    $ok = $update->execute([
        $title,
        $location,
        $salary,
        $description,
        $image_file,
        $job_id,
        $org_id
    ]);

    if ($ok) {
        header('Location: admin.php');
        exit;
    } else {
        echo "Error updating job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Job - Admin Dashboard</title>

<style>
    * { box-sizing: border-box; font-family: Arial, sans-serif; }
    body { margin: 0; display: flex; min-height: 100vh; background: #f4f6f9; }

    .flag-strip {
        height: 6px;
        background: linear-gradient(to right, #007A3D 33%, #CE1126 33%, #CE1126 66%, #FCD116 66%);
        position: fixed; top: 0; left: 0; right: 0; z-index: 10;
    }

    .sidebar {
        width: 260px; 
        background: #007A3D;
        padding: 25px; 
        color: white;
        position: fixed; 
        top: 6px;
        bottom: 0;
        border-right: 4px solid #CE1126;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #FCD116;
        font-size: 22px;
        font-weight: bold;
    }

    .sidebar a {
        display: block;
        padding: 12px;
        background: rgba(0,0,0,0.15);
        color: white;
        margin-bottom: 10px;
        border-radius: 6px;
        font-weight: bold;
        border-left: 5px solid transparent;
        text-decoration: none;
    }

    .sidebar a:hover {
        background: #CE1126;
        border-left: 5px solid #FCD116;
    }

    .main {
        margin-left: 280px;
        padding: 25px;
        flex-grow: 1;
    }

    h1 {
        color: #CE1126;
        font-size: 26px;
        border-left: 6px solid #007A3D;
        padding-left: 12px;
        margin-bottom: 20px;
    }

    form {
        background: white;
        padding: 25px;
        border-radius: 8px;
        border-left: 6px solid #007A3D;
        border-top: 4px solid #FCD116;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        max-width: 650px;
    }

    label {
        font-weight: bold;
        color: #007A3D;
        display: block;
        margin-top: 15px;
    }

    input[type="text"], textarea, input[type="file"] {
        width: 100%; 
        padding: 10px; 
        margin-top: 5px; 
        border-radius: 5px; 
        border: 1px solid #bbb;
    }

    button {
        background: #CE1126;
        color: white;
        padding: 12px 18px;
        border: none;
        border-radius: 5px;
        margin-top: 20px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    button:hover {
        background: #a50d1c;
    }

    .back-link {
        display: inline-block;
        margin-top: 15px;
        color: #007A3D;
        font-weight: bold;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .preview-image {
        margin-top: 10px;
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #007A3D;
    }
</style>
</head>

<body>

<div class="flag-strip"></div>

<div class="sidebar">
    <h2>JobPortal CM</h2>
    <a href="admin.php">🏠 Dashboard</a>
    <a href="post_job.php">➕ Post New Job</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <h1>Edit Job</h1>

    <form method="POST" enctype="multipart/form-data">

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($job['title']) ?>" required>

        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($job['location']) ?>" required>

        <label>Salary:</label>
        <input type="text" name="salary" value="<?= htmlspecialchars($job['salary']) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($job['description']) ?></textarea>

        <label>Upload New Image:</label>
        <input type="file" name="image">

        <p>Current Image:</p>
        <?php if (!empty($job['image'])) : ?>
            <img class="preview-image" src="uploads/<?= htmlspecialchars($job['image']) ?>" alt="Current Image">
        <?php else : ?>
            <p>No image available.</p>
        <?php endif; ?>

        <button type="submit" name="update">Update Job</button>
    </form>

    <a href="admin.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
