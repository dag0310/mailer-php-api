<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require_once('auth.php');

function send_email($config) {
  $mail = new PHPMailer;
  $mail->isSMTP();
  $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
  $mail->Host = $config['smtp_host']; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
  $mail->Port = $config['smtp_port']; // TLS only
  $mail->SMTPSecure = 'tls'; // ssl is depracated
  $mail->SMTPAuth = true;
  $mail->Username = $config['smtp_user'];
  $mail->Password = $config['smtp_pass'];
  $mail->setFrom($config['from_email'], $config['from_name']);
  $mail->addAddress($config['to_email']);
  $mail->Subject = $config['subject'];
  $mail->msgHTML($config['message_html']); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
  if (isset($config['message_plain'])) {
    $mail->AltBody = $config['message_plain'];
  }
  // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file

  $success = $mail->send();
  if (!$success) {
    echo "Mailer Error: {$mail->ErrorInfo}";
  }
  return $success;
}

$config_filename = 'config.ini';
$ini_config = parse_ini_file($config_filename, FALSE);
if ($ini_config === FALSE) {
  http_response_code(500);
  exit;
}

if (!isset($ini_config['api_token'])) {
  http_response_code(500);
  exit;
}

if ($ini_config['api_token'] !== get_bearer_token()) {
  http_response_code(401);
  exit;
}

$mail_config_keys = [
  'to_email',
  'subject',
  'message_html',
  'message_plain',
  'from_email',
  'from_name',
  'smtp_host',
  'smtp_user',
  'smtp_pass',
  'smtp_port',
];

$mail_config = [];
$missing_field_names = [];

foreach ($mail_config_keys as $key) {
  if ($key === 'message_plain' && !isset($_POST[$key])) {
    continue;
  }
  if (!isset($_POST[$key]) && !isset($ini_config[$key])) {
    $missing_field_names[] = $key;
    continue;
  }
  $mail_config[$key] = trim($_POST[$key] ?? $ini_config[$key]);
}

if (sizeof($missing_field_names) > 0) {
  $missing_field_names_str = implode(', ', $missing_field_names);
  http_response_code(400);
  echo "Missing field names: '{$missing_field_names_str}'";
  exit;
}

http_response_code(send_email($mail_config) ? 200 : 400);
