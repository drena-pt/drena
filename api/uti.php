<?php
#API - Obter dados de um utilizador
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

if ($_POST["uti"]){

    #Informações do perfil
    $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT id, nut, nco, fpe, dcr FROM uti WHERE nut='".$_POST["uti"]."'"));
    if (!$uti_perfil){
        echo '{"err": "Utilizador não encontrado."}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }

    /* if ($uti_perfil['ati']!=1){
        header('HTTP/1.1 400 Bad Request'); exit;
    } */

    $uti_perfil['fpe'] = $url_media.'fpe/'.$uti_perfil['fpe'].'.jpg';

    #Renderiza o output em json
    echo json_encode($uti_perfil);


} else if ($_POST["utis"]){

    $lista_utis = array_unique(json_decode(html_entity_decode($_POST["utis"]), true));
    $array_utis = array();

    foreach ($lista_utis as $utis) {
        #Informações do perfil
        $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT id, nut, nco, fpe, dcr FROM uti WHERE nut='".$utis."'"));
        if ($uti_perfil){
            $uti_perfil['fpe'] = $url_media.'fpe/'.$uti_perfil['fpe'].'.jpg';
            $array_utis[] = $uti_perfil;
        }
    }

    #Renderiza o output em json
    echo json_encode($array_utis);


#Pedido inválido
} else {
    header('HTTP/1.1 400 Bad Request'); exit;
}
?>