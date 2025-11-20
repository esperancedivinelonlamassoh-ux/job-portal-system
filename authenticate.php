<?php
session_start();
include ("DB.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // TODO: Replace this with your real login logic
    if ($email === "admin@jobportal.com" && $password === "admin123") {
        header("Location: home.php");
    } else {
        echo "<script>alert('Invalid credentials'); window.history.back();</script>";
    }
}
?>
