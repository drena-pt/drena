<?php
require('fun.php'); #FUNÇÕES
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."';"));											#Informações da media
if ($med){	#Se a media existir
	$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$_GET["id"]."' AND uti='".$uti['id']."';"));	#Informações do gosto

	if ($med_gos){
		$bd->query("DELETE FROM med_gos WHERE med='".$_GET["id"]."' AND uti='".$uti['id']."';");
		if ($bd->query("UPDATE med SET gos=gos-1 WHERE id='".$_GET["id"]."'") === FALSE) {
			echo "Erro:".$bd->error;
			exit;
		}
		echo "false";
	} else {
		$bd->query("INSERT INTO med_gos (uti, med) VALUES('".$uti['id']."', '".$_GET["id"]."');");
		if ($bd->query("UPDATE med SET gos=gos+1 WHERE id='".$_GET["id"]."'") === FALSE) {
			echo "Erro:".$bd->error;
			exit;
		}
		echo "true";
	}
	exit;
}
echo "Erro: A Media não existe.";
exit;
?>