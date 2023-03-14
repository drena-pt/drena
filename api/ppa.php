<?php
#API - Alteração/Registo de email
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');
#Função de Enviar Emails
require('../pro/ema.php');

$ac = $_POST['ac'];#Ação
$post_mai = $_POST['mai'];

#AÇÃO: Recuperar conta atravez de envio de email
if ($ac=='recuperar'){

	#Obtem o mais recente registo do email confirmado
	$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$post_mai."' AND con=1 ORDER BY id DESC LIMIT 1"));

	#Se existir algum registo do email confirmado
	if ($mai){
		#Informações do utilizador dono do email
		$mai_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$mai["uti"]."'"));
		$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($mai['ure']));

		if ($mai['ree']<=2 AND $tempoUltimoEmail>=300){


			$mail_subject = 'Recuperação da tua conta';
			$mail_body = "
			<div style='text-align:center;background-color:#6464ff;padding:6px;'>
				<a href='".$url_site."'><img height='32px' src='".$url_site."imagens/logo.png'></a>
			</div>
			<br>Olá ".$mai_uti['nco'].", parece que te esqueces-te da palavra-passe da tua conta <b>".$mai_uti['nut']."</b>...<br>
			Não faz mal! Está aqui o link para conseguires alterar a palavra-passe:<br>
			<a href='".$url_site."entrar?ac=alterarPasse&uti=".$mai_uti['nut']."&cod=".$mai['cod']."'>
			".$url_site."entrar?ac=alterarPasse&uti=".$mai_uti['nut']."&cod=".$mai['cod']."</a>
			<br><br>Tem um ótimo dia. 🙂";

			if (email($mai['mai'], $mail_subject, $mail_body)==true){
				#SQL - Regista envio e hora do email
				if ($bd->query("UPDATE uti_mai SET ree=ree+1, ure='".date("Y-m-d H:i:s")."' WHERE id='".$mai['id']."'") === FALSE){
					echo '{"err": "Erro ao registar envio e hora do email: '.$bd->error.'"}'; exit;
				}
				setcookie('mailEnviado', 1, time() + (60), "/");

				#Sucesso
				echo '{"est":"sucesso"}'; exit;

			} else {
				echo '{"err":"Não foi possível enviar o email"}'; exit;
			}
			

		} else {
			#AVISO: Excedeste o limite de envio de emails
			$aviso_mai=5; goto avisos;
		}
	} else {
		#AVISO: Email inválido
		$aviso_mai=4; goto avisos;
	}


#AÇÃO: Alterar palavra-passe
} else if ($ac=='alterar'){

	#Se o utilizador não estiver com sessão iniciada (não enviou token de autorização)
	if (!$uti){

		#Obtem informações do utilizador e do email
		$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."'"));
		$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti["mai"]."' AND con=1"));

		if (!$uti_mai){
			echo '{"err": "O utilizador ('.$_POST["uti"].') não tem um email de recuperação"}'; exit;
		}
		#Verifica se o código obtido pelo coincide com o código do email do utilizador
		if ($uti_mai['cod']!=$_POST['cod']){
			echo '{"err": "Operação negada"}'; exit;
		}

	}
			
	$ppa = $_POST["ppa"];
	$rppa = $_POST["rppa"];

	if (!$ppa){$aviso_ppa=1;}			#Aviso - Vaziu
	if (!$rppa){$aviso_rppa=1;}			#Aviso - Vaziu
	if ($ppa!=$rppa){$aviso_ppa = 6;} 	#Aviso - Palavras-passe diferentes.
	if ($aviso_ppa OR $aviso_rppa){ 	#Se houver avisos
		goto avisos;
	} else {
		$bd->query("UPDATE uti SET ppa='".password_hash($ppa, PASSWORD_DEFAULT)."' WHERE id='".$uti['id']."'");
		$bd->query("UPDATE uti_mai SET cod='".substr(md5(uniqid(rand(), true)), 8, 8)."', ree=0 WHERE id='".$uti_mai["id"]."'");

		#Sucesso
		echo '{"est":"sucesso"}';
	}


#AÇÃO INVÁLIDA
} else {
    echo '{"err": "Ação inválida"}';
    header('HTTP/1.1 400 Bad Request');
}
exit;



#AVISOS
avisos:
$avisos = array(
	"mai" => $aviso_mai,
	"cod" => $aviso_cod,
	"ppa" => $aviso_ppa,
	"rppa" => $aviso_rppa
);
#Envia um 'avi' (Aviso) porque o 'err' (Erro) faz um alert no browser.
echo '{"avi":'.json_encode($avisos).'}';
exit;
?>