<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require 'fun.php';

$lista = "SELECT * FROM med WHERE est IN (3,5)";
            if ($resultado = $bd->query($lista)) {
                 while ($campo = $resultado->fetch_assoc()) {
			echo "id: ".$campo['id']."<br>";
                    exec("php ".$dir_site."/pro/med_compressao.php ".$campo['id']." > /dev/null &");
                 } 
                $resultado->free();
            }

?>