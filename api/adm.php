<?php
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
include_once('validar.php');

if ($uti['car']!=1){
    header('HTTP/1.1 401 Unauthorized'); exit;
}

$campo = $_POST['campo'];

if (strstr($campo, 'ati')) {
	$tipo = "ati";
} else if (strstr($campo, 'car')) {
	$tipo = "car";
} else {
	echo '{"err":"Tipo inválido"}'; exit;
}

$id = str_ireplace($tipo, '', $campo);
$mudar = mysqli_fetch_assoc(mysqli_query($bd, "SELECT ".$tipo." FROM uti WHERE id='".$id."'"));
if ($mudar[$tipo]==1){
	$bool = 0;
} else {
	$bool = 1;
}

if ($bd->query("UPDATE uti SET ".$tipo."='".$bool."' WHERE id='".$id."'") === TRUE) {
	echo '{"est":"sucesso","'.$tipo.'":"'.$bool.'","uti":"'.$id.'"}';
} else {
	echo '{"err": "'.$bd->error.'"}';
}

exit;
?>