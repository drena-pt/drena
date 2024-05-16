<?php
#API - Obter médias (do prefil de um utilizador ou de um álbum)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

############################# Organizar isto para o futuro.

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
        $med_pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND alb IS NULL ".$pri_med." AND den < '".$depois['den']."' ORDER BY den DESC LIMIT 6";
    } else {
        $med_pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND alb IS NULL ".$pri_med." ORDER BY den DESC LIMIT 6";
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
        header('HTTP/1.0 404 Not Found'); exit;
    }

    #Oculta publicações privadas se não for o mesmo utilizador
    if ($uti['id']!=$alb['uti']){
        $pri_med = "AND pri=0";
    }

    #Informações do Dono do albúm
    $alb_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$alb["uti"]."'"));

    #Pesquisa Medias do albúm
    $alb_pesquisa = "SELECT id,thu,tip,tit,pri,gos,den FROM med WHERE alb='".$alb['id']."' ".$pri_med." ORDER BY den DESC";

    $meds = array();
    if ($resultado = $bd->query($alb_pesquisa)) {
        while ($med = $resultado->fetch_assoc()) {
            $med['tit_curto'] = encurtarNome($med['tit']);
            $med['thu'] = $url_media.'thumb/'.$med['thu'].'.jpg'; #Coloca o url completo, em vez de apenas o id
            #Procura por um gosto do utilizador
            $med['tem_gos'] = mysqli_num_rows($bd->query("SELECT * FROM med_gos WHERE med='".$med['id']."' AND uti='".$uti['id']."'"));
            $meds[] = $med;
        }
    }
    $alb_uti_info = array('nut' => $alb_uti['nut'], 'fpe' => $url_media.'fpe/'.$alb_uti['fpe'].'.jpg');
    $output = array('uti' => $alb_uti_info, 'meds' => $meds);

#Pedido inválido
} else {
    header('HTTP/1.1 400 Bad Request'); exit;
}

#Renderiza o output em json
echo json_encode($output);

?>