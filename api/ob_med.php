<?php
#API - Obter médias (das páginas de prefil dos utilizadores)
#Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
#Base de dados
require_once('bd.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$output = array();

#Informações do utilizador
$uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."'"));
if (!$uti_perfil){
    echo '{"err": "Utilizador não encontrado."}';
    header('HTTP/1.1 400 Bad Request'); exit;
}

#Encurtar nome
function encurtarNome($nome, $tamanho=19){
    if (strlen($nome)>=$tamanho){
        return (mb_substr($nome, 0, $tamanho-1)."…");
    } else {
        return ($nome);
    }
}

#Informações da média por onde deve começar a pesquisa
$depois = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST['depois']."'"));
if ($depois){
    $med_pesquisa = "SELECT id,thu,tip,tit FROM med WHERE den < '".$depois['den']."' AND uti='".$uti_perfil['id']."' AND pri=0 ORDER BY den DESC LIMIT 6";
} else {
    $med_pesquisa = "SELECT id,thu,tip,tit FROM med WHERE uti='".$uti_perfil['id']."' AND pri=0 ORDER BY den DESC LIMIT 6";
}

if ($resultado = $bd->query($med_pesquisa)) {
    while ($med = $resultado->fetch_assoc()) {
        $med['tit_curto'] = encurtarNome($med['tit']);
        $med['thu'] = $url_media.'thumb/'.$med['thu'].'.jpg'; #Coloca o url completo, em vez de apenas o id
        $output[] = $med;
    }
}

#Renderiza o output em json
echo json_encode($output);

?>