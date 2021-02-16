<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();

function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 

    return $key;
}
$codigodobem = gerarCodigo(16);
$uti = 5;
$nome_ficheiro = "botsoboio.mp4";
$tipo = 1;
echo $codigodobem." este é o codigodobem<br>".$codigodobem;
if ($bd->query("INSERT INTO med (id, uti, nom, tip) VALUES('".$codigodobem."', '".$uti."', '".$nome_ficheiro."', '".$tipo."');") === FALSE) {
	echo "Error:".$bd->error;
	exit;
}

?>