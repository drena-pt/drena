<?php
require_once ('ligarbd.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
session_start();

$post_mai = $_POST['mai'];

if ($_SESSION["pre_uti"]){					# Se houver sessão de pre-utilizador iniciada.
	$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["pre_uti"]."'"));
	$mai_atual = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."' AND uti='".$uti['id']."'"));
	if ($_GET["ac"]=='registarMail'){
		# Procurar na base de dados pelo mail já confirmado por outro utilizador.
		$mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$post_mai."' AND con=1"));
		if ($mai_confirmado){$erro_mai=3;goto erros;}				# Se o email já estiver confirmado e em uso.
		if ($mai_atual['mai']==$post_mai){$erro_mai=7;goto erros;}		# Se o novo email for o mesmo que o atual.
		if (!$post_mai){$erro_mai=1;goto erros;}					# Se o campo de email estiver vaziu.
					
		# SQL - Criar registo do mail e código de confirmação.
		if ($bd->query("INSERT INTO uti_mai (uti, mai, cod)	VALUES ('".$uti['id']."', '".$post_mai."', '".substr(md5(uniqid(rand(), true)), 8, 8)."')") === FALSE){
			echo "Erro ao criar registo do mail e código de confirmação: ".$bd->error;
			exit;
		} else {
			echo "fase 1";
		}
		# Ultimo registo de mail do utilizador.
		$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE uti='".$uti['id']."' AND mai='".$post_mai."' ORDER BY id DESC"));

		# SQL - Atualizar o id do mail no registo do utilizador.
		if ($bd->query("UPDATE uti SET mai='".$mai['id']."' WHERE id='".$uti['id']."'") === FALSE){
			echo "Erro ao registar novo id de email no utilizador: ".$bd->error;
			exit;
		} else {
			echo "fase 2";
		}

		$enviarMail = true;
		goto enviarMail;
	} else if ($_GET["ac"]=='reenviarMail'){
		echo "oi";
		exit;
	} else if ($_GET["ac"]=='confirmar'){
		$con = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE cod='".$_POST['cod']."' AND mai='".$mai_atual['mai']."' AND con=0"));
		if ($con){
			$bd->query("UPDATE uti_mai SET con='1' WHERE id='".$con['id']."'");
			$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$con['uti']."'"));
			session_start();
			$_SESSION["pre_uti"] = null;
			$_SESSION["uti"] = $uti['nut'];
			setcookie('bem-vindo', 1, time() + (4), "/");
			header("Location: ../perfil?uti=".$uti['nut']);
			exit;
		} else {
			$erro_cod=6;
			goto erros;
		}
	}
}
exit;

enviarMail:
if ($enviarMail==true){
	$mail = new PHPMailer(true);
	try {
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host       = 'smtp.sapo.pt';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'drenaxyz@sapo.pt';
		$mail->Password   = 'Kz2YeDRMJzie';
		$mail->Port       = 587;
		$mail->CharSet    = 'UTF-8';
		$mail->Encoding   = 'base64';
		$mail->setFrom('drenaxyz@sapo.pt', 'drena');
		$mail->addAddress($mai['mai']);
		$mail->isHTML(true);
		$mail->Subject    = 'Confirmação de registo';
		$mail->Body       = "<div style='text-align:center;background-color:#6464ff;padding:6px;'><a href='https://drena.xyz'><img height='32px' src='https://2.drena.xyz/imagens/logo.png'></a></div><br>Olá ".$uti['nco'].", obrigado por te registares na drena!<br>Está aqui o código para confirmares a criação da tua conta <b>".$uti['nut']."</b>:<br><br><h1>".$mai['cod']."</h1><br><br>Tem um ótimo dia. 🙂";
		$mail->send();
	} catch (Exception $e) {
		echo "Erro, o mail não pode ser enviado: {$mail->ErrorInfo}";
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}


erros:
$erros = array(
	"mai" => $erro_mai,
	"cod" => $erro_cod
);
setcookie('erros', serialize($erros), time() + (4), "/");
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>