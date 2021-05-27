<?php
require_once ('ligarbd.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
session_start();

$post_mai = $_POST['mai'];

if ($_SESSION["pre_uti"]){ # Se houver sessão de pre-utilizador iniciada.
	$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["pre_uti"]."'"));
	$mai_atual = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."' AND uti='".$uti['id']."'"));

	if ($_GET["ac"]=='registarMail'){

		if ($mai_atual['mai']==$post_mai){$erro_mai=7;goto erros;}		# Se o novo email for o mesmo que o atual.
		if (!$post_mai){$erro_mai=1;goto erros;}						# Se o campo de email estiver vaziu.

		# Procurar na base de dados se o email já foi confirmado.
		$mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$post_mai."' AND con=1"));
		if ($mai_confirmado){$erro_mai=3;goto erros;}					# Se o email já estiver confirmado e em uso.
					
		# SQL - Criar registo do email e código de confirmação.
		if ($bd->query("INSERT INTO uti_mai (uti, mai, cod)	VALUES ('".$uti['id']."', '".$post_mai."', '".substr(md5(uniqid(rand(), true)), 8, 8)."')") === FALSE){
			echo "Erro ao criar registo do mail e código de confirmação: ".$bd->error;
			exit;
		}

		# Obtem o mais recente registo de email do utilizador.
		$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE uti='".$uti['id']."' AND mai='".$post_mai."' ORDER BY id DESC"));

		# SQL - Atualizar o id do email no registo do utilizador.
		if ($bd->query("UPDATE uti SET mai='".$mai['id']."' WHERE id='".$uti['id']."'") === FALSE){
			echo "Erro ao registar novo id de email no utilizador: ".$bd->error;
			exit;
		}

		$enviarMail = 1;
		goto enviarMail;

	} else if ($_GET["ac"]=='reenviarMail'){

		# Tempo desdo envio do ultimo mail.
		$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($mai_atual['ure']));
		if ($mai_atual AND $mai_atual['ree']<=2 AND $tempoUltimoEmail>=300){
			$mai = $mai_atual;
			$enviarMail = 1;
			goto enviarMail;
		} else {
			echo "Erro: não podes reenviar o mail.";
		}

	} else if ($_GET["ac"]=='confirmar'){

		# Se o email já estiver confirmado e em uso.
		$mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$mai_atual['mai']."' AND con=1"));
		if ($mai_confirmado){echo "Erro, este mail já foi confirmado noutra conta.";exit;}		
				
		$con = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE cod='".$_POST['cod']."' AND mai='".$mai_atual['mai']."' AND con=0"));
		if ($con){
			$bd->query("UPDATE uti_mai SET con='1', ree='0', dco='".date("Y-m-d H:i:s")."' WHERE id='".$con['id']."'");
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

} else { # Se não houver uma sessão de pre-utilizador.

	if ($_GET["ac"]=='recuperar'){ # Ação - Recuperar conta atravez de envio de email.
		# Obtem o mais recente registo do email confirmado.
		$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$post_mai."' AND con=1 ORDER BY id DESC LIMIT 1"));

		if ($mai){ # Se existir algum registo do email confirmado.

			# Informações do utilizador dono do email ativo.
			$mai_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$mai["uti"]."'"));
			$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($mai['ure']));
			if ($mai['ree']<=2 AND $tempoUltimoEmail>=300){
				$enviarMail = 2;
				goto enviarMail;
			} else { # Erro - Excedes-te o limite de envio de emails
				$erro_mai=5;
				goto erros;
			}

		} else {
			$erro_mai=4;
			goto erros;
		}

	} else if ($_GET["ac"]=='alterarPasse'){ # Ação - Alterar palavra-passe.

		# Obtem informações do utilizador do GET
		$get_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));
		$get_uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$get_uti["mai"]."' AND con=1"));

		# Verifica se o código obtido pelo GET coincide com o mail do utilizador
		if ($get_uti_mai['cod']==$_GET['cod']){
			
			$ppa = $_POST["ppa"];
			$rppa = $_POST["rppa"];

			if (!$ppa){$erro_ppa=1;}				# Vaziu - Palavra-passe
			if (!$rppa){$erro_rppa=1;}				# Vaziu - Palavra-passe repetida
			if ($ppa!=$rppa){$erro_ppa = 6;} 		# Erro - Palavras-passe diferentes.
			if ($erro_ppa OR $erro_rppa){ 			# Se houver erros
				goto erros;
			} else {
				$bd->query("UPDATE uti SET ppa='".password_hash($ppa, PASSWORD_DEFAULT)."' WHERE nut='".$get_uti['nut']."'");
				
				setcookie('passeAlterada', 1, time() + (60), "/");
				header("Location: /../entrar?ac=alterarPasse");
				exit;
			}

		}

	}

}
exit;

enviarMail:
if ($enviarMail){

	if ($enviarMail==1){
		$mail_subject = 'Confirmação de registo';
		$mail_body = "<div style='text-align:center;background-color:#6464ff;padding:6px;'><a href='https://drena.xyz'><img height='32px' src='https://drena.xyz/imagens/logo.png'></a></div><br>Olá ".$uti['nco'].", obrigado por te registares na drena!<br>Está aqui o código para confirmares a criação da tua conta <b>".$uti['nut']."</b>:<br><br><h1>".$mai['cod']."</h1><br><br>Tem um ótimo dia. 🙂";
	} else if ($enviarMail==2){
		$mail_subject = 'Recuperação da tua conta';
		$mail_body = "<div style='text-align:center;background-color:#6464ff;padding:6px;'><a href='https://drena.xyz'><img height='32px' src='https://drena.xyz/imagens/logo.png'></a></div><br>Olá ".$mai_uti['nco'].", parece que te esqueces-te da palavra-passe da tua conta <b>".$mai_uti['nut']."</b>...<br>Não faz mal! Está aqui o link para conseguires alterar a palavra-passe:<br><a href='https://drena.xyz/entrar?ac=alterarPasse&uti=".$mai_uti['nut']."&cod=".$mai['cod']."'>https://drena.xyz/entrar?ac=alterarPasse&uti=".$mai_uti['nut']."&cod=".$mai['cod']."</a><br><br>Tem um ótimo dia. 🙂";
	}
	
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
		$mail->Subject    = $mail_subject;
		$mail->Body       = $mail_body;
		$mail->send();
	} catch (Exception $e) {
		echo "Erro, o mail não pode ser enviado: {$mail->ErrorInfo}";
		exit;
	}

	# SQL - Registar envio e hora do mail.
	if ($bd->query("UPDATE uti_mai SET ree=ree+1, ure='".date("Y-m-d H:i:s")."' WHERE id='".$mai['id']."'") === FALSE){
		echo "Erro ao registar envio e hora do mail: ".$bd->error;
		exit;
	}
	
	if ($enviarMail=2){
		setcookie('mailEnviado', 1, time() + (60), "/");
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}


erros:
$erros = array(
	"mai" => $erro_mai,
	"cod" => $erro_cod,
	"ppa" => $erro_ppa,
	"rppa" => $erro_rppa
);
setcookie('erros', serialize($erros), time() + (4), "/");
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>