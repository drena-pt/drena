<?php
require('fun.php'); #FUNÇÕES
$sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='".base64_decode($_GET["id"])."'"));			#Informações da secção
$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".$sec['pro']."'"));								#Informações Projeto
if ($pro['uti']==$uti['id']){		#Se o utilizador for dono do projeto
	if ($bd->query("UPDATE pro_sec SET ati=0 WHERE id='".$sec['id']."'") === FALSE) {
		echo "Erro:".$bd->error;
	}
	exit();
}
echo "Erro: Não podes apagar a secção.";
exit();
?>