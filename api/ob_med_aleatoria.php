<?php
#API - Obter média aleatória (para a página inicial)
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

#Obtem uma média aleatória
$query = $bd->query("SELECT * FROM med WHERE pri='0' AND tip='3' ORDER BY RAND() LIMIT 1;");

if (mysqli_num_rows($query)){
    #Obtem dados da média
    $query_assoc = mysqli_fetch_assoc($query);
    
    #Obtem dados do utilizador dono da média
    $query_uti = $bd->query("SELECT * FROM uti WHERE id='".$query_assoc['uti']."'");
    $assoc_uti = mysqli_fetch_assoc($query_uti);
    $output = array("med"=>$query_assoc,"uti"=>["nut"=>$assoc_uti['nut'],"fpe"=>$url_media.'fpe/'.$assoc_uti['fpe'].'.jpg']);
        
} else {
    echo '{"err": "Erro ao obter média"}';
    header('HTTP/1.1 500 Internal Server Error'); exit;
}

#Renderiza o output em JSON
echo json_encode($output);
?>