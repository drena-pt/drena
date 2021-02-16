<?php
ob_start();
require_once ('ligarbd.php');
ob_get_clean();

$con = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE cod='".$_GET['cod']."' AND con=0"));
if ($con){
	$bd->query("UPDATE uti_mai SET con='1' WHERE id='".$con['id']."'");
	$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$con['uti']."'"));
	session_start();
	$_SESSION["pre_uti"] = null;
	$_SESSION["uti"] = $uti['nut'];
	setcookie('bem-vindo', 1, time() + (4), "/");
	header("Location: ../perfil.php?uti=".$uti['nut']);
	exit();
} else {
	echo "<h1>Erro: Código de confirmação inválido</h1>";
}

$bd->close();
exit;
?>