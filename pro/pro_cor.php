<?php
require('fun.php'); #FUNÇÕES
$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["pro"])."'"));		 				#Informações Projeto
if ($pro['uti']==$uti['id']){		#Se o utilizador for dono do projeto

	function corParaNumero($cor){
		switch ($cor) {
			case 'azul': return 1; break;
			case 'verde': return 2; break;
			case 'amarelo': return 3; break;
			case 'vermelho': return 4; break;
			case 'rosa': return 5; break;
			case 'ciano': return 6; break;
			case 'primary': return 7; break;
			default: return 0;
		}
	}

	if ($bd->query("UPDATE pro SET cor=".corParaNumero($_GET["cor"])." WHERE id='".$pro['id']."'") === FALSE) {
		echo "Erro:".$bd->error;
	} else {
		echo "nova cor: ".$_GET["cor"];
	}
	exit();
}
echo "Erro: Não podes editar o projeto.";
exit();
?>