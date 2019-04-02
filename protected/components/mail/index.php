<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "msa.hinet.net";

$mail->SetFrom("godsly0921@gmail.com", "godsly0921@gmail.com");
$mail->AddAddress("godsly0921@gmail.com");
$mail->AddCC("godsly0529@gmail.com");

$mail->Subject = "測試";
$mail->Body = "123132132";
$res = $mail->Send();
var_dump($res);
?>