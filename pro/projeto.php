<?php
require('fun.php'); #FUNÇÕES

#Verificar se o título não é nulo.
if (!$_POST["tit_input"]){
	echo "<h1>Erro; O título não pode estar vazio.</h1>\n POST(tit) = ".$_POST["tit_input"];
	exit;
}

$sql = "INSERT INTO pro (uti, tit)
VALUES ('".$uti["id"]."', '".$_POST['tit_input']."')";

if (mysqli_query($bd, $sql)) {
	$ultimo = mysqli_insert_id($bd);
	header("location: ../projeto?id=".base64_encode($ultimo));
} else {
	echo "Erro: " . $sql . "<br>" . $bd->error;
}
exit;
?>