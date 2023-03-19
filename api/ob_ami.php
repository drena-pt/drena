<?php
#API - Obter amigos de um utilizador
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

$output = array();

if ($_POST["uti"]){

    #Informações do perfil
    $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."'"));
    if (!$uti_perfil){
        echo '{"err": "Utilizador não encontrado"}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }

    $sql_conhecidos = "SELECT a_id, b_id FROM ami WHERE a_id='".$uti_perfil["id"]."' AND sim='1' OR b_id='".$uti_perfil["id"]."' AND sim='1' ORDER by b_dat DESC";
	$todos_conhecidos = mysqli_query($bd, $sql_conhecidos);

    while ($ami = $todos_conhecidos->fetch_row()){
        if ($ami[0]==$uti_perfil["id"]){
            $ami_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT nut,nco,fpe FROM uti WHERE id='".$ami[1]."'"));
        } else {
            $ami_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT nut,nco,fpe FROM uti WHERE id='".$ami[0]."'"));
        }
        $ami_uti['fpe'] = $url_media.'fpe/'.$ami_uti['fpe'].'.jpg';

        $output[] = $ami_uti;
    }

    #Renderiza o output em json
    echo json_encode($output);

#Pedido inválido
} else {
    echo '{"err": "Pedido inválido"}';
    header('HTTP/1.1 400 Bad Request'); exit;
}
?>