<?php
require('fun.php'); #FUNÇÕES
$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["pro"])."'"));		 				#Informações Projeto
if ($pro['uti']==$uti['id']){		#Se o utilizador for dono do projeto
	if ($bd->query("UPDATE pro SET cor=".str_replace('cor','',$_GET["cor"])." WHERE id='".$pro['id']."'") === FALSE) {
		echo "Erro:".$bd->error;
	}
	exit();
}
echo "Erro: Não podes apagar a secção.";
exit();
?>