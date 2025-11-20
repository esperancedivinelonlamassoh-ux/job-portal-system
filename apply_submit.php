<?php
session_start();
include 'DB.php'; // make sure this connects to your database

// ✅ Check login status
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>
        alert('Please log in first to apply for a job.');
        window.location.href='login_applicant.php';
    </script>";
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

// ✅ If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'] ?? 0;
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter'] ?? '');

    if ($job_id == 0) {
        die("Invalid job ID.");
    }

    // ✅ Handle file uploads
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $cv_path = '';
    $id_card_path = '';

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $cv_name = time() . "_" . basename($_FILES["cv"]["name"]);
        $cv_target = $upload_dir . $cv_name;
        move_uploaded_file($_FILES["cv"]["tmp_name"], $cv_target);
        $cv_path = $cv_target;
    }

    if (isset($_FILES['id_card']) && $_FILES['id_card']['error'] === UPLOAD_ERR_OK) {
        $id_name = time() . "_" . basename($_FILES["id_card"]["name"]);
        $id_target = $upload_dir . $id_name;
        move_uploaded_file($_FILES["id_card"]["tmp_name"], $id_target);
        $id_card_path = $id_target;
    }

    // ✅ Insert into applications table
    $query = "INSERT INTO applications (job_id, applicant_id, cover_letter, cv, id_card)
              VALUES ('$job_id', '$applicant_id', '$cover_letter', '$cv_path', '$id_card_path')";

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
<html>
<head>
    <meta charset="utf-8">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Apply for Job</h2>
    <form method="post" enctype="multipart/form-data">
      
        <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">


         <label>Full Name:</label>
        <input type="text" name="name" required class="form-control"><br>

        <label>Email:</label>
        <input type="email" name="email" required class="form-control"><br>

        <label>Phone:</label>
        <input type="text" name="phone" required class="form-control"><br>

        <label>Cover Letter:</label>
        <textarea name="cover_letter" required class="form-control"></textarea><br>

        <label>Upload CV (PDF):</label>
        <input type="file" name="cv" accept=".pdf" required class="form-control"><br>

        <label>Upload ID Card (JPG/PNG):</label>
        <input type="file" name="id_card" accept=".jpg,.jpeg,.png" required class="form-control"><br>

        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>
</body>
</html>
