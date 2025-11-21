<<<<<<< HEAD
<?php
// contact.php

include 'DB.php'; // Include your DB connection

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if ($name && $email && $subject && $message) {
        $sql = "INSERT INTO contact_messages (name, email, subject, message, date_sent)
                VALUES ('$name', '$email', '$subject', '$message', NOW())";
        if (mysqli_query($conn, $sql)) {
            $success = "👏 Your message has been received! We’ll get back to you soon.";
        } else {
            $error = "⚠️ Oops! Something went wrong. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Cameroon Job Portal</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f6f8fa;
        margin: 0;
        color: #222;
    }

    header {
        background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
        color: white;
        padding: 20px 0;
        text-align: center;
    }

    header h1 {
        margin: 0;
        font-size: 28px;
        letter-spacing: 1px;
    }

    nav a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-weight: 600;
    }

    nav a:hover {
        text-decoration: underline;
    }

    .container {
        width: 90%;
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h1 {
        color: #007a3d;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input, textarea {
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    button {
        background: #007a3d;
        color: white;
        border: none;
        padding: 12px 20px;
        margin-top: 20px;
        cursor: pointer;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s;
    }

    button:hover {
        background: #005e2e;
    }

    .success {
        color: green;
        margin-top: 10px;
        font-weight: bold;
    }

    .error {
        color: red;
        margin-top: 10px;
        font-weight: bold;
    }

    .contact-info {
        margin-top: 30px;
        background: #f0f0f0;
        padding: 15px;
        border-radius: 5px;
    }

    footer {
        margin-top: 40px;
        background: #222;
        color: white;
        text-align: center;
        padding: 20px 0;
        font-size: 14px;
    }

    footer p {
        margin: 5px 0;
    }

    footer .flag {
        color: #fcd116;
        font-weight: bold;
    }
</style>
</head>
<body>

<header>
    <h1>Cameroon JobPortal</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="jobs.php">Jobs</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<div class="container">
    <h1>Get in Touch With Us</h1>
    <p>Have questions, feedback, or need assistance? We’re right here in Cameroon to help you succeed in your career journey!</p>
    
    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>

    <form action="" method="post">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="e.g. Lonla Esperance" required>

        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="e.g. lonla@example.com" required>

        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" placeholder="e.g. Job Application Help" required>

        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5" placeholder="Type your message here..." required></textarea>

        <button type="submit">Send Message</button>
    </form>

    <div class="contact-info">
        <h3>📞 Reach Us Directly</h3>
        <p><strong>Email:</strong> support@cmjobportal.com</p>
        <p><strong>Phone (MTN):</strong> +237 677 123 456</p>
        <p><strong>Phone (Orange):</strong> +237 699 987 654</p>
        <p><strong>Address:</strong> Great Soppo, Buea — South West Region, Cameroon</p>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Cameroon Job Portal. All Rights Reserved.</p>
    <p class="flag">🇨🇲 Made with pride in Cameroon.</p>
</footer>

</body>
</html>
=======
<?php
// contact.php

include 'DB.php'; // Include your DB connection

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if ($name && $email && $subject && $message) {
        $sql = "INSERT INTO contact_messages (name, email, subject, message, date_sent)
                VALUES ('$name', '$email', '$subject', '$message', NOW())";
        if (mysqli_query($conn, $sql)) {
            $success = "👏 Your message has been received! We’ll get back to you soon.";
        } else {
            $error = "⚠️ Oops! Something went wrong. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Cameroon Job Portal</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f6f8fa;
        margin: 0;
        color: #222;
    }

    header {
        background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
        color: white;
        padding: 20px 0;
        text-align: center;
    }

    header h1 {
        margin: 0;
        font-size: 28px;
        letter-spacing: 1px;
    }

    nav a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-weight: 600;
    }

    nav a:hover {
        text-decoration: underline;
    }

    .container {
        width: 90%;
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h1 {
        color: #007a3d;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input, textarea {
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    button {
        background: #007a3d;
        color: white;
        border: none;
        padding: 12px 20px;
        margin-top: 20px;
        cursor: pointer;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s;
    }

    button:hover {
        background: #005e2e;
    }

    .success {
        color: green;
        margin-top: 10px;
        font-weight: bold;
    }

    .error {
        color: red;
        margin-top: 10px;
        font-weight: bold;
    }

    .contact-info {
        margin-top: 30px;
        background: #f0f0f0;
        padding: 15px;
        border-radius: 5px;
    }

    footer {
        margin-top: 40px;
        background: #222;
        color: white;
        text-align: center;
        padding: 20px 0;
        font-size: 14px;
    }

    footer p {
        margin: 5px 0;
    }

    footer .flag {
        color: #fcd116;
        font-weight: bold;
    }
</style>
</head>
<body>

<header>
    <h1>Cameroon JobPortal</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="jobs.php">Jobs</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<div class="container">
    <h1>Get in Touch With Us</h1>
    <p>Have questions, feedback, or need assistance? We’re right here in Cameroon to help you succeed in your career journey!</p>
    
    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>

    <form action="" method="post">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="e.g. Lonla Esperance" required>

        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="e.g. lonla@example.com" required>

        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" placeholder="e.g. Job Application Help" required>

        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5" placeholder="Type your message here..." required></textarea>

        <button type="submit">Send Message</button>
    </form>

    <div class="contact-info">
        <h3>📞 Reach Us Directly</h3>
        <p><strong>Email:</strong> support@cmjobportal.com</p>
        <p><strong>Phone (MTN):</strong> +237 677 123 456</p>
        <p><strong>Phone (Orange):</strong> +237 699 987 654</p>
        <p><strong>Address:</strong> Great Soppo, Buea — South West Region, Cameroon</p>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Cameroon Job Portal. All Rights Reserved.</p>
    <p class="flag">🇨🇲 Made with pride in Cameroon.</p>
</footer>

</body>
</html>
>>>>>>> 76038d0 (commit changes)
