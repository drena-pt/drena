<?php
require('fun.php');		# Obter funções
$ac = $_GET['ac'];		# Ação

#Verificar se o título não é nulo.
/*if (!$_POST["tit_input"]){
	echo "<h1>Erro; O título não pode estar vazio.</h1>\n POST(tit) = ".$_POST["tit_input"];
	exit;
}*/

if ($ac=='criar'){

	$cor_aleatoria = rand(0,7);

	$sql = "INSERT INTO pro (uti, tit, cor)
	VALUES ('".$uti["id"]."', '".$_POST['tit_input']."', '".$cor_aleatoria."')";

	if (mysqli_query($bd, $sql)) {
		$ultimo = mysqli_insert_id($bd);
		header("location: ../projeto?id=".base64_encode($ultimo));
	} else {
		echo "Erro: " . $sql . "<br>" . $bd->error;
	}
} else {
	
	$projeto = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET['id'])."'"));

	if ($projeto AND $projeto['uti']==$uti['id']){ # Se o projeto existir e o utilizador conectado for o dono

		if ($ac=='eliminar'){
	
			if ($bd->query("DELETE FROM pro_sec WHERE pro='".$projeto['id']."'") === FALSE) {
				echo "Erro: ".$bd->error;
			} else if ($bd->query("DELETE FROM pro WHERE id='".$projeto['id']."'") === FALSE) {
				echo "Erro: ".$bd->error;
			} else {
				header("Location: /perfil?uti=".$uti['nut']);
			}
		} else {
			echo "Erro: Nenhuma ação selecionada.";
		}
	} else {
		echo "Erro: Projeto inválido.";
	}
}
exit;
?>