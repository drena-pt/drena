<?php
ob_start();
require_once ('pro/ligarbd.php');
ob_get_clean();

$uti_fot = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_fot WHERE id='".base64_decode($_GET["id"])."'"));
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$uti_fot["uti"]."'"));

header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . date(DATE_RFC822,strtotime("31 day")));
header("content-type: image/jpeg");

if ($uti['fot']!=null){
	echo $uti_fot['fot'];
} else {
	echo file_get_contents("imagens/padrao.jpg");
}
exit;
?>