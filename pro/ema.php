<?php
#Processo - Enviar emails
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

#Funções
require_once(__DIR__."/fun.php");
#Composer
require_once(__DIR__."/../vendor/autoload.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function email($mail_address, $mail_subject, $mail_body){
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();
		$mail->Host       = $ema_host;
		$mail->SMTPAuth   = true;
		$mail->Username   = $ema_host;
		$mail->Password   = $ema_psswd;
		$mail->Port       = 587;
		$mail->CharSet    = 'UTF-8';
		$mail->Encoding   = 'base64';
		$mail->setFrom($ema_host, 'drena');
		$mail->addAddress($mail_address);
		$mail->isHTML(true);
		$mail->Subject    = $mail_subject;
		$mail->Body       = $mail_body;
		$mail->send();
	} catch (Exception $e) {
		return false;
		#echo '{"err": "Erro, o mail não pode ser enviado: '.$mail->ErrorInfo.'"}'; exit;
	}
	#Sucesso
	return true;
}
?>