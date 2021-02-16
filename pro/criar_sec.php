<?php
require('fun.php'); #FUNÇÕES

$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["pro"])."'"));	#Informações Projeto
$pro_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$pro["uti"]."'"));				#Informações Utilizador do projeto
if ($pro_uti['id']==$uti['id']){	#Se o utilizador for dono do projeto

	$sql = "INSERT INTO pro_sec (pro)
	VALUES ('".$pro["id"]."')";

	if (!mysqli_query($bd, $sql)){
		echo "Error: " . $sql . "<br>" . $bd->error;
	}
	exit();
}
echo "Erro: Não podes criar a secção.";
exit();
?>