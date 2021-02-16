<?php
ob_start();
require_once ('ligarbd.php');
ob_get_clean();

$nut = $_POST["nut"];
$nco = $_POST["nco"];
#$_POST["mai"];
$ppa = $_POST["ppa"];
$rppa = $_POST["rppa"];

if ($nut==''or$nco==''or$ppa==''or$rppa==''or$mai=''){$erro_campos = 1;}												#Erro - Campos vazios
if ($ppa!=$rppa){$erro_ppa = 1;}																						#Erro - Palavras passes diferentes
if (mysqli_num_rows(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"))!=null){$erro_nut = 1;}					#Erro - Utilizador já registado
if (!ctype_alnum($nut)){$erro_nut = 1;}
if (mysqli_num_rows(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$_POST["mai"]."' and con=1"))){$erro_mai = 1;}#Erro - Mail já registado
if ($erro_campos == 1 OR $erro_ppa == 1 OR $erro_nut == 1 OR $erro_mai == 1){											#Erros (caso haja)
	$erros = array(
		"campos" => $erro_campos,
		"ppa" => $erro_ppa,
		"nut" => $erro_nut,
		"mai" => $erro_mai,
	);
	setcookie('erros', serialize($erros), time() + (4), "/");
	header("location: ../registo.php");
	exit;
}

$sql_uti = "INSERT INTO uti (nut, nco, ppa)
VALUES ('".$nut."', '".$nco."', '".password_hash($ppa, PASSWORD_DEFAULT)."')";
if ($bd->query($sql_uti) === FALSE) { #Cria utilizador na bd.
	echo "Erro: ".$sql_uti."<br>".$bd->error;
	exit;
}
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"));

$sql_uti_mai = "INSERT INTO uti_mai (uti, mai, cod)
VALUES ('".$uti['id']."', '".$_POST["mai"]."', '".substr(md5(uniqid(rand(), true)), 8, 8)."')";
if ($bd->query($sql_uti_mai) === FALSE) {
	echo "Erro: ".$sql_uti_mai."<br>".$bd->error;
	exit;
}
$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE uti='".$uti['id']."' ORDER BY id DESC"));

$sql_uti_mai = "UPDATE uti SET mai='".$mai['id']."' WHERE id='".$uti['id']."'"; #Define o mail na tabela utilizador.
if ($bd->query($sql_uti_mai) === FALSE) {
	echo "Erro: ".$sql_uti_mai."<br>".$bd->error;
	exit;
}

session_start();
#Inicia sessão pre utilizador sem mail confirmado
/*$_SESSION["pre_uti"] = $nut; 
header("location: registo.mai.php");*/

#Inicia sessão e mostra boas vindas
setcookie('bem-vindo', 1, time() + (4), "/");
$_SESSION["uti"] = $uti['nut'];
header("location: ../perfil?uti=".$nut);

$bd->close();
exit();
?>