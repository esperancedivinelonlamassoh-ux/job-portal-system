<<<<<<< HEAD
<?php
// about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - JobConnect Cameroon</title>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f2f5f7;
      margin: 0;
      padding: 0;
      color: #333;
    }

    /* 🇨🇲 HEADER */
    header {
      background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
      color: white;
      text-align: center;
      padding: 25px 0;
    }

    header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    nav {
      margin-top: 10px;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
      transition: opacity 0.3s ease;
    }

    nav a:hover {
      opacity: 0.8;
    }

    /* MAIN CONTAINER */
    .container {
      width: 90%;
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #007a3d;
      border-left: 5px solid #ce1126;
      padding-left: 10px;
      margin-bottom: 10px;
    }

    p, li {
      line-height: 1.6;
      font-size: 16px;
    }

    ul {
      margin-left: 25px;
    }

    /* CALL TO ACTION */
    .cta {
      background: #004aad;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      transition: background 0.3s ease;
    }

    .cta:hover {
      background: #003580;
    }

    /* FOOTER 🇨🇲 */
    footer {
      background: #004aad;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      font-size: 14px;
    }

    .flag {
      display: inline-block;
      width: 25px;
      height: 15px;
      background: linear-gradient(to right, #007a3d 33%, #ce1126 33%, #ce1126 66%, #fcd116 66%);
      border: 1px solid white;
      margin-left: 5px;
      vertical-align: middle;
    }
  </style>
</head>
<body>

<header>
  <h1>Cameroon JobPortal</h1>
  <nav>
    <a href="home.php">Home</a>
    <a href="jobs.php">Jobs</a>
    <a href="recommended_jobs.php">Recommended</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
  <h2>About JobConnect Cameroon</h2>
  <p>
    Welcome to <strong>JobConnect.cm</strong> — Cameroon’s trusted online platform connecting talented individuals
    with companies and organizations across the country.  
    From Douala to Bamenda, Buea to Yaoundé, we help job seekers and employers meet effortlessly.
  </p>

  <h2>Our Mission</h2>
  <p>
    Our mission is simple: <strong>to bridge the gap between talent and opportunity in Cameroon</strong>.  
    We aim to empower young professionals, students, and experienced workers to access meaningful employment opportunities locally.
  </p>

  <h2>How It Works</h2>
  <ul>
    <li><strong>For Job Seekers:</strong> Create your free profile, upload your CV, and apply for jobs anywhere in Cameroon.</li>
    <li><strong>For Employers:</strong> Post vacancies, view applications, and recruit the best local talent in just a few clicks.</li>
  </ul>

  <h2>Why Choose JobConnect.cm?</h2>
  <ul>
    <li>Focused on Cameroonian jobs and industries</li>
    <li>Verified employers to ensure job authenticity</li>
    <li>Clean, user-friendly design for all devices</li>
    <li>Totally free for job seekers — because opportunity should be accessible to all</li>
  </ul>

  <h2>Meet Our Team</h2>
  <p>
    We’re a dedicated team of Cameroonian software engineers, designers, and career enthusiasts.  
    Our goal is to make finding and offering jobs across Cameroon — from startups in Buea to NGOs in Yaoundé — fast and reliable.
  </p>

  <div class="section">
    <a href="contact.php" class="cta">Get in Touch</a>
  </div>
</div>

<footer>
  <p>© <?php echo date('Y'); ?> JobConnect.cm — Made with ❤️ in Cameroon<span class="flag"></span></p>
</footer>

</body>
</html>
=======
<?php
// about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - JobConnect Cameroon</title>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f2f5f7;
      margin: 0;
      padding: 0;
      color: #333;
    }

    /* 🇨🇲 HEADER */
    header {
      background: linear-gradient(90deg, #007a3d, #ce1126, #fcd116);
      color: white;
      text-align: center;
      padding: 25px 0;
    }

    header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    nav {
      margin-top: 10px;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
      transition: opacity 0.3s ease;
    }

    nav a:hover {
      opacity: 0.8;
    }

    /* MAIN CONTAINER */
    .container {
      width: 90%;
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #007a3d;
      border-left: 5px solid #ce1126;
      padding-left: 10px;
      margin-bottom: 10px;
    }

    p, li {
      line-height: 1.6;
      font-size: 16px;
    }

    ul {
      margin-left: 25px;
    }

    /* CALL TO ACTION */
    .cta {
      background: #004aad;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      transition: background 0.3s ease;
    }

    .cta:hover {
      background: #003580;
    }

    /* FOOTER 🇨🇲 */
    footer {
      background: #004aad;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      font-size: 14px;
    }

    .flag {
      display: inline-block;
      width: 25px;
      height: 15px;
      background: linear-gradient(to right, #007a3d 33%, #ce1126 33%, #ce1126 66%, #fcd116 66%);
      border: 1px solid white;
      margin-left: 5px;
      vertical-align: middle;
    }
  </style>
</head>
<body>

<header>
  <h1>Cameroon JobPortal</h1>
  <nav>
    <a href="home.php">Home</a>
    <a href="jobs.php">Jobs</a>
    <a href="recommended_jobs.php">Recommended</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
  </nav>
</header>

<div class="container">
  <h2>About JobConnect Cameroon</h2>
  <p>
    Welcome to <strong>JobConnect.cm</strong> — Cameroon’s trusted online platform connecting talented individuals
    with companies and organizations across the country.  
    From Douala to Bamenda, Buea to Yaoundé, we help job seekers and employers meet effortlessly.
  </p>

  <h2>Our Mission</h2>
  <p>
    Our mission is simple: <strong>to bridge the gap between talent and opportunity in Cameroon</strong>.  
    We aim to empower young professionals, students, and experienced workers to access meaningful employment opportunities locally.
  </p>

  <h2>How It Works</h2>
  <ul>
    <li><strong>For Job Seekers:</strong> Create your free profile, upload your CV, and apply for jobs anywhere in Cameroon.</li>
    <li><strong>For Employers:</strong> Post vacancies, view applications, and recruit the best local talent in just a few clicks.</li>
  </ul>

  <h2>Why Choose JobConnect.cm?</h2>
  <ul>
    <li>Focused on Cameroonian jobs and industries</li>
    <li>Verified employers to ensure job authenticity</li>
    <li>Clean, user-friendly design for all devices</li>
    <li>Totally free for job seekers — because opportunity should be accessible to all</li>
  </ul>

  <h2>Meet Our Team</h2>
  <p>
    We’re a dedicated team of Cameroonian software engineers, designers, and career enthusiasts.  
    Our goal is to make finding and offering jobs across Cameroon — from startups in Buea to NGOs in Yaoundé — fast and reliable.
  </p>

  <div class="section">
    <a href="contact.php" class="cta">Get in Touch</a>
  </div>
</div>

<footer>
  <p>© <?php echo date('Y'); ?> JobConnect.cm — Made with ❤️ in Cameroon<span class="flag"></span></p>
</footer>

</body>
</html>
>>>>>>> 76038d0 (commit changes)
