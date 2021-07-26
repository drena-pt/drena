<?php
$funcoes['requerSessao'] = 0;
require 'fun.php'; #Funções

$fot = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_fot WHERE id='".base64_decode($_GET["id"])."'"));
$uti_fot = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$fot["uti"]."'"));

header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . date(DATE_RFC822,strtotime("31 day")));
header("content-type: image/jpeg");

if ($uti_fot['fot']!=null){
	echo $fot['fot'];
} else {
	echo file_get_contents("imagens/padrao.jpg");
}
exit;
?>