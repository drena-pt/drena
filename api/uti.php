<?php
#API - Obter dados de um utilizador
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

if ($_POST["uti"]){

    #Informações do perfil
    $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."'"));
    if (!$uti_perfil){
        echo '{"err": "Utilizador não encontrado."}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }

    /* if ($uti_perfil['ati']!=1){
        header('HTTP/1.1 400 Bad Request'); exit;
    } */

    unset($uti_perfil['id']);
    unset($uti_perfil['ppa']);
    unset($uti_perfil['mai']);
    unset($uti_perfil['rno']);
    unset($uti_perfil['ati']);
    unset($uti_perfil['car']);
    $uti_perfil['fpe'] = $url_media.'fpe/'.$uti_perfil['fpe'].'.jpg';

    #Renderiza o output em json
    echo json_encode($uti_perfil);

#Pedido inválido
} else {
    header('HTTP/1.1 400 Bad Request'); exit;
}
?>