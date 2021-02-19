<?php
ob_start();
require_once ('ligarbd.php');
ob_get_clean();

# Torna os posts em variáveis
$nut = $_POST["nut"];
$nco = $_POST["nco"];
$mai = $_POST["mai"];
$ppa = $_POST["ppa"];
$rppa = $_POST["rppa"];

if (!$nut){$erro_nut=1;}							# Vaziu - Nome de utilizador
if (!$nco){$erro_nco=1;}							# Vaziu - Nome verdadeiro
if (!$mai){$erro_mai=1;}							# Vaziu - Email
if (!$ppa){$erro_ppa=1;}							# Vaziu - Palavra-passe
if (!$rppa){$erro_rppa=1;}							# Vaziu - Palavra-passe repetida

if ($ppa!=$rppa){$erro_ppa = 2;}																					# Erro - Palavras-passe diferentes.
if (mysqli_num_rows(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$mai."' and con=1"))){$erro_mai = 3;}		# Erro - Mail já registado e confirmado.
if ($nut){																											# Se o utilizador não for null:
	if (mysqli_num_rows(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"))){$erro_nut = 4;}				# Erro - Nome de utilizador já registado.
	if (!ctype_alnum($nut)){$erro_nut = 5;}																			# Erro - Nome de utilizador não alfanumérico.
}
if ($erro_nut OR $erro_nco OR $erro_mai OR $erro_ppa OR $erro_rppa){												# Se houver erros, volta para a página com um cookie dos erros.
	$erros = array(
		"nut" => $erro_nut,
		"nco" => $erro_nco,
		"mai" => $erro_mai,
		"ppa" => $erro_ppa,
		"rppa" => $erro_rppa
	);
	setcookie('erros', serialize($erros), time() + (4), "/");
	header("location: ../registo");
	exit;
}

$sql_uti = "INSERT INTO uti (nut, nco, ppa)													# SQL - Criar utilizador.
VALUES ('".$nut."', '".$nco."', '".password_hash($ppa, PASSWORD_DEFAULT)."')";
if ($bd->query($sql_uti) === FALSE){														# Caso ocorra um erro na criação do utilizador:
	echo "Erro ao registar utilizador: ".$sql_uti."<br>".$bd->error;
	exit;
}
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"));		# A variável vai buscar as informações do utilizador à base de dados.

$sql_uti_mai = "INSERT INTO uti_mai (uti, mai, cod)											# SQL - Criar informação do mail e código de confirmação.
VALUES ('".$uti['id']."', '".$mai."', '".substr(md5(uniqid(rand(), true)), 8, 8)."')";
if ($bd->query($sql_uti_mai) === FALSE){													# Caso ocorra um erro no registo do mail e código:
	echo "Erro ao registar email: ".$sql_uti_mai."<br>".$bd->error;
	exit;
}
$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE uti='".$uti['id']."' ORDER BY id DESC"));	# A variável vai buscar as informações do mail à base de dados.

$sql_uti_mai = "UPDATE uti SET mai='".$mai['id']."' WHERE id='".$uti['id']."'";				# SQL - Atualiza e define o mail na tabela utilizador.
if ($bd->query($sql_uti_mai) === FALSE) {													# Caso ocorra um erro ao atualizar a tabela do utilizador:
	echo "Erro ao atualizar email no registo do utilizador: ".$sql_uti_mai."<br>".$bd->error;
	exit;
}

session_start();							# Inicia sessão pre-utilizador sem mail confirmado.
$_SESSION["pre_uti"] = $nut;
$bd->close();
header("location: registo.mai.php");
exit;

#Inicia sessão e mostra boas vindas
/*setcookie('bem-vindo', 1, time() + (4), "/");
$_SESSION["uti"] = $uti['nut'];
header("location: ../perfil?uti=".$nut);


exit;*/
?>