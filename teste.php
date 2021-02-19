<?php
require 'pro/ligarbd.php';

$sql = "update uti set ppa='".password_hash('123456', PASSWORD_DEFAULT)."' where nut='phi19'";
if ($bd->query($sql) === FALSE) {
	echo "Erro: ".$sql."<br>".$bd->error;
	exit;
}
?>