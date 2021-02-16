<?php
ob_start();
require_once ('ligarbd.php');
ob_get_clean();

$nut = $_POST["nut"];
$ppa = $_POST["ppa"];

$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"));

if($nut==null or $uti['id']==null or $nut!=$uti['nut']){
	header("Location: ../entrar");
	exit();
} else if ($uti['ati']==0){
	header("Location: ../entrar");
	exit();
}

if(password_verify($ppa, $uti['ppa'])){
	session_start();
	#Verificação do mail
	/*
	$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
	if ($uti_mai['con']==0){
		$_SESSION["pre_uti"] = $nut;
		header("Location: ../registo");
		exit();
	}
	*/
	$_SESSION["uti"] = $uti['nut'];
	header("Location: ../");
	exit();
} else {
	header("Location: ../entrar");
	exit();
}
?>