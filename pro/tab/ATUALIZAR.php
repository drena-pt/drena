<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}

if ($resultado = $bd->query("SELECT * FROM uti_mai;")) {
    while ($campo = $resultado->fetch_assoc()) {
        echo $campo['id'];
        
        #Em utilização
        $uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE mai='".$campo['id']."';"));

        if ($uti_mai){
            echo " ".$campo['mai']." em utilização ".$campo['con']."<br>";
        } else {
            echo " ".$campo['mai']." inutilizado ".$campo['con']."<br>";
            $bd->query("UPDATE uti_mai SET con='0' WHERE id='".$campo['id']."'");
        }
    } 
    $resultado->free();
}

echo "<br><h1>FIM</h1>";
exit;
?>