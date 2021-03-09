<?php
require('fun.php'); #FUNÇÕES
$sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='".base64_decode($_GET["sec"])."'"));			#Informações da secção
$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".$sec['pro']."'"));								#Informações Projeto
if ($pro['uti']==$uti['id']){		#Se o utilizador for dono do projeto
	if ($sec['vis']==0){
		$bd->query("UPDATE pro_sec SET vis=1 WHERE id='".$sec['id']."'");
		echo "true";
	} else {
		$bd->query("UPDATE pro_sec SET vis=0 WHERE id='".$sec['id']."'");
		echo "‎false";
	}
	exit;
}
echo "Erro: Não podes alterar a visibilidade.";
exit;
?>