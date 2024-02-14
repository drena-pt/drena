<?php
#API - Registo
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');
#JWT
use Firebase\JWT\JWT;

#Torna os posts em variáveis
$nut = $_POST["nut"];
$nco = $_POST["nco"];
$ppa = $_POST["ppa"];
$rppa = $_POST["rppa"];

if (!$nut){$erro_nut=1;}	#Vaziu - Nome de utilizador
if (!$nco){$erro_nco=1;}	#Vaziu - Nome verdadeiro
if (!$ppa){$erro_ppa=1;}	#Vaziu - Palavra-passe
if (!$rppa){$erro_rppa=1;}	#Vaziu - Palavra-passe repetida

if ($ppa!=$rppa){$erro_ppa = 2;}																					# Erro - Palavras-passe diferentes.
if ($nut){																											# Se o utilizador não for null:
	if (mysqli_num_rows(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"))){$erro_nut = 4;}				# Erro - Nome de utilizador já registado.
	if (preg_match('/[^a-zA-Z0-9çÇ_]/', $nut)){$erro_nut=5;} 														# Erro - Utilizador contem caracteres especiais.
}

#ERROS
if ($erro_nut OR $erro_nco OR $erro_ppa OR $erro_rppa){												# Se houver erros, volta para a página com um cookie dos erros.
	$erros = array(
		"nco" => $erro_nco,
		"nut" => $erro_nut,
		"ppa" => $erro_ppa,
		"rppa" => $erro_rppa
	);
	#Envia um 'avi' (Aviso) porque o 'err' (Erro) faz um alert no browser.
	echo '{"avi":'.json_encode($erros).'}';
} else {
	$sql_uti = "INSERT INTO uti (nut, nco, ppa)													# SQL - Criar utilizador.
	VALUES ('".$nut."', '".$nco."', '".password_hash($ppa, PASSWORD_DEFAULT)."')";
	if ($bd->query($sql_uti) === FALSE){														# Caso ocorra um erro na criação do utilizador:
		echo '{"err": "Erro mysqli: '.$bd->error.'}'; exit;
	}

	#Cria o Token JWT
	$jwt_date      = new DateTimeImmutable();
	$jwt_expire_at = $jwt_date->modify('+1 year')->getTimestamp();
	$jwt_data = [
		'iat'  => $jwt_date->getTimestamp(),
		'iss'  => $url_dominio,
		'exp'  => $jwt_expire_at,
		'sub'  => $nut,
	];
	$token = JWT::encode(
		$jwt_data,
		$api_key, #Obtem das variáveis
		'HS512'
	);
	setcookie('drena_token', $token, $jwt_expire_at, '/', '.'.$url_dominio);
	
	#Inicia sessão pre-utilizador sem mail confirmado.
	session_start();
	$_SESSION["pre_uti"] = $nut;
	echo '{"est":"sucesso","token":"'.$token.'"}';
}
exit; 
?>