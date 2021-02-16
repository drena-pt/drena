<?php
require('fun.php'); #FUNÇÕES
$sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='".base64_decode($_GET["sec"])."'"));			#Informações da secção
$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".$sec['pro']."'"));								#Informações Projeto
if ($pro['uti']==$uti['id']){		#Se o utilizador for dono do projeto
	if ($sec['tit_ati']==0){
		$bd->query("UPDATE pro_sec SET tit_ati=1 WHERE id='".$sec['id']."'");
		echo $sec['tit'];
	} else {
		$bd->query("UPDATE pro_sec SET tit_ati=0 WHERE id='".$sec['id']."'");
		echo "‎";
	}
	exit;
}
echo "Erro: Não podes alterar a visibilidade.";
exit;
?>