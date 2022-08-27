<?php
    /*error_reporting(E_ALL);
    ini_set('display_errors', 'On');*/

    #Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    include_once('bd.php');

    session_start();
    
    if ($_SESSION["uti"]){
        $uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));

        # Verificar se a conta está ativa
        if ($uti['ati']==0){ echo "A tua conta foi desativada por um administrador."; session_destroy(); exit; }

        $uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));

        echo '{"uti": "'.$uti['nut'].'", "cod": "'.$uti_mai['cod'].'"}';
    } else {
        echo '{"err": "user não logado"}';
        exit;
    }
    exit;

?>