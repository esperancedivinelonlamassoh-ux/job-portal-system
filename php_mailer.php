Use PHPMailer:
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_email@example.com';
$mail->Password = 'email_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('no-reply@jobportal.com', 'Job Portal');
$mail->addAddress($applicant_email);
$mail->Subject = 'Your Online Interview Link';
$mail->Body = "Dear Applicant,\n\nYour interview is scheduled at $interview_time\nJoin here: $zoom_link\n\nRegards,\nJob Portal";
$mail->send();

if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!=='applicant'){ header('Location: login_applicant.php'); exit; }
$job_id = intval($_GET['job_id'] ?? 0);
$pdo = DB::get();
$stmt = $pdo->prepare('SELECT * FROM jobs WHERE id = ? LIMIT 1');
$stmt->execute([$job_id]);
$job = $stmt->fetch();