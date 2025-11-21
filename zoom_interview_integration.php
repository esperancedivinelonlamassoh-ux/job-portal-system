<?php
function createZoomMeeting($topic, $start_time) {
    $apiKey = 'YOUR_ZOOM_API_KEY';
    $apiSecret = 'YOUR_ZOOM_API_SECRET';

    $data = json_encode([
        'topic' => $topic,
        'type' => 2, // Scheduled meeting
        'start_time' => $start_time,
        'duration' => 30,
        'timezone' => 'UTC',
        'settings' => [
            'join_before_host' => false,
            'mute_upon_entry' => true,
            'waiting_room' => true
        ]
    ]);

    $ch = curl_init("https://api.zoom.us/v2/users/me/meetings");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . generateJWT($apiKey, $apiSecret),
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);

    return $result['join_url'] ?? null; // This is the Zoom meeting link
}

function generateJWT($apiKey, $apiSecret) {
    $header = json_encode(['alg'=>'HS256','typ'=>'JWT']);
    $payload = json_encode([
        'iss'=>$apiKey,
        'exp'=>time() + 3600
    ]);

    $base64UrlHeader = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
    $base64UrlPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $apiSecret, true);
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}


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

?>


 