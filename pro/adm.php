<?php
require 'fun.php'; #Funções
if ($uti['car']!=1){
	header("Location: ../entrar");
	exit();
}

$campo = $_GET['campo'];

if (strstr($campo, 'ati')) {
	$tipo = "ati";
} else if (strstr($campo, 'adm')) {
	$tipo = "adm";
} else {
	exit;
}

$id = str_ireplace($tipo, '', $campo);
if ($mudar = mysqli_fetch_assoc(mysqli_query($bd, "SELECT ".$tipo." FROM uti WHERE id='".$id."'"))){
	if ($mudar[$tipo]==1){
		$bool = 0;
	} else {
		$bool = 1;
	}
}
if ($bd->query("UPDATE uti SET ".$tipo."='".$bool."' WHERE id='".$id."'") === FALSE) {
	echo "Error:".$bd->error;
}
?>