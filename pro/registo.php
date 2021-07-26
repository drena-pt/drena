<?php
$funcoes['requerSessao'] = 0;
require 'fun.php'; #Funções

# Torna os posts em variáveis
$nut = $_POST["nut"];
$nco = $_POST["nco"];
$ppa = $_POST["ppa"];
$rppa = $_POST["rppa"];

if (!$nut){$erro_nut=1;}							# Vaziu - Nome de utilizador
if (!$nco){$erro_nco=1;}							# Vaziu - Nome verdadeiro
if (!$ppa){$erro_ppa=1;}							# Vaziu - Palavra-passe
if (!$rppa){$erro_rppa=1;}							# Vaziu - Palavra-passe repetida

if ($ppa!=$rppa){$erro_ppa = 2;}																					# Erro - Palavras-passe diferentes.
if ($nut){																											# Se o utilizador não for null:
	if (mysqli_num_rows(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"))){$erro_nut = 4;}				# Erro - Nome de utilizador já registado.
	if (preg_match('/[^a-zA-Z0-9çÇ_]/', $nut)){$erro_nut=5;} 														# Erro - Utilizador contem caracteres especiais.
}
if ($erro_nut OR $erro_nco OR $erro_ppa OR $erro_rppa){												# Se houver erros, volta para a página com um cookie dos erros.
	$erros = array(
		"nut" => $erro_nut,
		"nco" => $erro_nco,
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

# Inicia sessão pre-utilizador sem mail confirmado.
session_start();
$_SESSION["pre_uti"] = $nut;
$bd->close();
header("location: /../registo");
exit;
?>