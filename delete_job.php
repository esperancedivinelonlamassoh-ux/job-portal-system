<<<<<<< HEAD
<?php
include("DB.php");
session_start();

// Only org users
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$job_id = $_GET['id'];

// Delete job if it belongs to this organization
$delete_query = "DELETE FROM jobs WHERE id='$job_id' AND organization_id='$org_id'";

if (mysqli_query($conn, $delete_query)) {
    header('Location: admin.php');
    exit;
} else {
    echo "Error deleting job: " . mysqli_error($conn);
}
?>
=======
<?php
include("DB.php");
session_start();

// Only org users
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'org_user') {
    header('Location: login_org.php');
    exit;
}

$org_id = $_SESSION['org_id'];

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$job_id = $_GET['id'];

// Delete job if it belongs to this organization
$delete_query = "DELETE FROM jobs WHERE id='$job_id' AND organization_id='$org_id'";

if (mysqli_query($conn, $delete_query)) {
    header('Location: admin.php');
    exit;
} else {
    echo "Error deleting job: " . mysqli_error($conn);
}
?>
>>>>>>> 76038d0 (commit changes)
