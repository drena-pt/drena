<?php
#API - Obter médias (das páginas de prefil dos utilizadores)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$output = array();

#Encurtar nome
function encurtarNome($nome, $tamanho=19){
    if (strlen($nome)>=$tamanho){
        return (mb_substr($nome, 0, $tamanho-1)."…");
    } else {
        return ($nome);
    }
}

#Carregar publicações do perfil
if ($_POST["uti"]){

    #Informações do perfil
    $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."'"));
    if (!$uti_perfil){
        echo '{"err": "Utilizador não encontrado."}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }

    #Informações da média por onde deve começar a pesquisa
    $depois = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST['depois']."'"));

    #Oculta publicações privadas se não for o mesmo utilizador
    if ($uti['nut']!=$uti_perfil['nut']){
        $pri_med = "AND pri=0";
    }

    #SQL Pesquisa
    if ($depois){
        $med_pesquisa = "SELECT id,thu,tip,tit,pri FROM med WHERE uti='".$uti_perfil['id']."' AND alb IS NULL ".$pri_med." AND den < '".$depois['den']."' ORDER BY den DESC LIMIT 6";
    } else {
        $med_pesquisa = "SELECT id,thu,tip,tit,pri FROM med WHERE uti='".$uti_perfil['id']."' AND alb IS NULL ".$pri_med." ORDER BY den DESC LIMIT 6";
    }

    if ($resultado = $bd->query($med_pesquisa)) {
        while ($med = $resultado->fetch_assoc()) {
            $med['tit_curto'] = encurtarNome($med['tit']);
            $med['thu'] = $url_media.'thumb/'.$med['thu'].'.jpg'; #Coloca o url completo, em vez de apenas o id
            $output[] = $med;
        }
    }

#Carregar publicações de um albúm
} else if ($_POST["alb"]){

    #Informações do Albúm
    $alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$_POST["alb"]."'"));
    if (!$alb){
        echo '{"err": "Albúm não encontrado."}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }

    #Oculta publicações privadas se não for o mesmo utilizador
    if ($uti['id']!=$alb['uti']){
        $pri_med = "AND pri=0";
    }

    #SQL Pesquisa
    $alb_pesquisa = "SELECT id,thu,tip,tit,pri FROM med WHERE alb='".$alb['id']."' ".$pri_med." ORDER BY den DESC";

    if ($resultado = $bd->query($alb_pesquisa)) {
        while ($med = $resultado->fetch_assoc()) {
            $med['tit_curto'] = encurtarNome($med['tit']);
            $med['thu'] = $url_media.'thumb/'.$med['thu'].'.jpg'; #Coloca o url completo, em vez de apenas o id
            $output[] = $med;
        }
    }

#Pedido inválido
} else {
    header('HTTP/1.1 400 Bad Request'); exit;
}

#Renderiza o output em json
echo json_encode($output);

?>