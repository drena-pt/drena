<?php
session_start();
if ($_SESSION["pre_uti"]==null){
	exit;
}
require_once ('ligarbd.php');
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["pre_uti"]."'"));
$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
if ($mai['ree']==0){
	echo "<h1>Erro, não podes reenviar mail de confirmação, verifica o teu mail</h1>";
	exit;
} else {
	$bd->query("UPDATE uti_mai SET ree=ree-1 WHERE id='".$mai['id']."'");
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
$mail = new PHPMailer(true);
try {
	#$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->isSMTP();
	$mail->Host       = 'box.drena.xyz';
	$mail->SMTPAuth   = true;
	$mail->Username   = 'registo@drena.xyz';
	$mail->Password   = 'Kz2YeDRMJzie';
	$mail->Port       = 587;
	$mail->CharSet    = 'UTF-8';
	$mail->Encoding   = 'base64';
	$mail->setFrom('registo@drena.xyz', 'drena');
	$mail->addAddress($mai['mai']);
	#$mail->addReplyTo('info@example.com', 'Information');
	#$mail->addCC('cc@example.com');
	#$mail->addBCC('bcc@example.com');
	#$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	#$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = 'Confirmação de registo';
	#$mail->Body    = "Olá ".$uti['nco'].", obrigado por te registares na drena!<br>Clica no botão a baixo para confirmares a criação da tua conta <b>".$uti['nut']."</b>:<br><br><a href='drena.xyz/registo.php?con=".$mai['cod']."' style='background-color:#6464ff;padding:0.4rem 1.2rem;color:#fff;border: none;border-radius:10rem;font-family:sans-serif;'>Confirmar conta</a><br><br>Tem um ótimo dia,<br>drena";
	$mail->Body    = "Olá ".$uti['nco'].", obrigado por te registares na drena!<br>Está aqui o código para confirmares a criação da tua conta <b>".$uti['nut']."</b>:<br><br><h1>".$mai['cod']."</h1><br><br>Tem um ótimo dia,<br>drena";
	#$mail->AltBody = "Olá ";
	$mail->send();
	header("Location: ../registo.php");
	exit;
} catch (Exception $e) {
	echo "Erro, o mail não pode ser enviado: {$mail->ErrorInfo}";
	exit;
}
?>