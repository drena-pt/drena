<?php
ob_start();
require_once ('ligarbd.php');
ob_get_clean();
date_default_timezone_set('Europe/Lisbon');
session_start();
if ($_SESSION["uti"]==null){
	header("Location: ../entrar.php");
	exit;
}
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));
?>