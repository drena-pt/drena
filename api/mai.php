<?php
#API - Alteração/Registo de email
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');
#Função de Enviar Emails
require('../pro/ema.php');

$ac = $_POST['ac']; #Ação
$post_mai = $_POST['mai'];

#Mail atual do utilizador
$mai_atual = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."' AND uti='".$uti['id']."'"));



#AÇÃO: Registar Mail
if ($ac=='registar'){

	if (!$post_mai){$aviso_mai=1;goto avisos;}						#AVISO: Se o campo de email estiver vaziu
	if ($mai_atual['mai']==$post_mai){$aviso_mai=7;goto avisos;}		#AVISO: Se o novo email for o mesmo que o atual

	#Procurar na base de dados se o email já foi confirmado
	$mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$post_mai."' AND con=1"));
	if ($mai_confirmado){$aviso_mai=3;goto avisos;}					#AVISO: Se o email já estiver confirmado e em uso

	#SQL - Define o email atual como não confirmado (pois já não vai ser usado)
	if ($bd->query("UPDATE uti_mai SET con=0 WHERE id='".$mai_atual['id']."'") === FALSE){
		echo '{"err": "Erro ao desativar email atual: '.$bd->error.'}'; exit;
	}

	#SQL - Criar registo do email e código de confirmação
	if ($bd->query("INSERT INTO uti_mai (uti, mai, cod)	VALUES ('".$uti['id']."', '".$post_mai."', '".substr(md5(uniqid(rand(), true)), 8, 8)."')") === FALSE){
		echo '{"err": "Erro ao criar registo do mail e código de confirmação: '.$bd->error.'}'; exit;
	}

	#Obtem o mais recente registo de email do utilizador
	$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE uti='".$uti['id']."' AND mai='".$post_mai."' ORDER BY id DESC"));

	#SQL - Atualizar o id do email no registo do utilizador
	if ($bd->query("UPDATE uti SET mai='".$mai['id']."' WHERE id='".$uti['id']."'") === FALSE){
		echo '{"err": "Erro ao registar novo id de email no utilizador: '.$bd->error.'}'; exit;
	}

	#Se existir sessão de utilizador, passa para pre utilizador
	session_start();
	if ($_SESSION["uti"]){
		$_SESSION["pre_uti"] = $uti['nut'];
		$_SESSION["uti"] = null;
	}

	goto enviarMail;



#AÇÃO: Reenviar Mail
} else if ($ac=='reenviar'){

	#Tempo desde o ultimo envio de um mail
	$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($mai_atual['ure']));
	#Se o utilizador tiver algum email associado, se reenviou menos de 2 vezes uma confirmação e se já se passaram mais de 300 segundos desde o ultimo reenvio
	if ($mai_atual AND $mai_atual['ree']<=2 AND $tempoUltimoEmail>=300){
		$mai = $mai_atual;
		goto enviarMail;
	} else {
		echo '{"err": "Não podes reenviar outro mail"}'; exit;
	}



#AÇÃO: Confirmação do email com código
} else if ($ac=='confirmar'){

	$cod = $_POST['cod']; #Código de confirmação

	#ERRO: Se o email já estiver confirmado e em uso
	$mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$mai_atual['mai']."' AND con=1"));
	if ($mai_confirmado){
		echo '{"err": "Erro, este mail já foi confirmado noutra conta"}'; exit;
	}		

	#Confirmação
	$con = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE cod='".$cod."' AND mai='".$mai_atual['mai']."' AND con=0"));
	if ($con){
		$bd->query("UPDATE uti_mai SET con='1', ree='0', dco='".date("Y-m-d H:i:s")."' WHERE id='".$con['id']."'");
		session_start();
		$_SESSION["pre_uti"] = null;
		$_SESSION["uti"] = $uti['nut'];
		setcookie('bem-vindo', 1, time() + (4), "/");

		#Sucesso
		echo '{"est":"sucesso"}'; exit;
	} else {
		$aviso_cod=6;
		goto avisos;
	}

}
exit;



enviarMail:

$mail_subject = 'Confirmação de registo';
$mail_body = "
<div style='text-align:center;background-color:#6464ff;padding:6px;'>
	<a href='".$url_site."'>
		<img height='32px' src='".$url_site."imagens/logo.png'>
	</a>
</div><br>
Olá ".$uti['nco'].", obrigado por te registares na drena!<br>
Está aqui o código para confirmares a ativação da tua conta <b>".$uti['nut']."</b>:<br><br>
<h1>".$mai['cod']."</h1>
<br><br>
Tem um ótimo dia. 🙂";

if (email($mai['mai'], $mail_subject, $mail_body)==true){
	#SQL - Registar envio e hora do mail.
	if ($bd->query("UPDATE uti_mai SET ree=ree+1, ure='".date("Y-m-d H:i:s")."' WHERE id='".$mai['id']."'") === FALSE){
		echo '{"err": "Erro ao registar envio e hora do mail: '.$bd->error.'"}'; exit;
	}

	#Sucesso
	echo '{"est":"sucesso"}';

} else {
	echo '{"err":"Não foi possível enviar o email"}';
	header('HTTP/1.1 503 Service Temporarily Unavailable');
}
exit;


avisos:
$avisos = array(
	"mai" => $aviso_mai,
	"cod" => $aviso_cod,
	"ppa" => $aviso_ppa,
	"rppa" => $aviso_rppa
);
#Envia um 'avi' (Aviso) porque o 'err' (Erro) faz um alert no browser.
echo '{"avi":'.json_encode($avisos).'}'; exit;
?>