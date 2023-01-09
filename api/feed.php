<?php
#API - Feed
#Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
#Base de dados
require_once('bd.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$output = array();

$depois = $_GET['depois'];    #Apresentar media depois do id defenido

if ($_GET['tip']=='global'){

    #Query de media ordenada por data limite 4
    $sql_media = "select t.*
    from med t cross join (select * from med where id = '".$depois."') c
    where t.den < c.den or (t.den = c.den and t.id >= c.id) and t.pri=0
    order by t.den desc, t.id
    limit 1,4";

    if (!$depois OR !mysqli_num_rows($bd->query($sql_media))){
        $sql_media = "SELECT * FROM med where pri=0 ORDER by den DESC LIMIT 3";
    }

} else {
    $sql_conhecidos = "SELECT * FROM ami WHERE a_id='".$_GET['uti']."' AND sim='1' OR b_id='".$_GET['uti']."' AND sim='1' ORDER by b_dat DESC";
    $conhecidos = (mysqli_query($bd, $sql_conhecidos)->fetch_assoc());
    
    $lista_feed = $_GET['uti'];
    if ($conhecidos){
        if ($resultado = $bd->query($sql_conhecidos)){
            while ($campo = $resultado->fetch_assoc()){
                #Adiciona os utilizadores à lista
                if ($campo['a_id']==$_GET['uti']){
                    $lista_feed .= ','.$campo['b_id'];
                } else {
                    $lista_feed .= ','.$campo['a_id'];
                }
            }
        }

        #Query de media ordenada por data limite 4
        $sql_media = "select t.*
        from med t cross join (select * from med where id = '".$depois."') c
        where t.uti IN (".$lista_feed.") AND t.den < c.den or (t.den = c.den and t.id >= c.id) and t.pri=0
        order by t.den desc, t.id
        limit 1,4";

        if (!$depois OR !mysqli_num_rows($bd->query($sql_media))){
            $sql_media = "SELECT * FROM med WHERE pri=0 and uti IN (".$lista_feed.") ORDER by den DESC LIMIT 3";
        }

    } else {
        $output_erro = "uti(".$_GET['id'].") não tem amigos.";
        $output[] = array("erro"=>$output_erro);
        goto output;
    }
}

if ($resultado = $bd->query($sql_media)) {
    while ($med = $resultado->fetch_assoc()) {
        #Obtem dados do utilizador dono da média
        $assoc_uti = ($bd->query("SELECT * FROM uti WHERE id='".$med['uti']."'")->fetch_assoc());
        #Procura por um registo de gosto do utilizador
        $assoc_gos = mysqli_num_rows($bd->query("SELECT * FROM med_gos WHERE med='".$med['id']."' AND uti='".$_GET['uti']."'"));
        $output[] = array("med"=>$med,"uti"=>["nut"=>$assoc_uti['nut'],"fpe"=>$url_media."fpe/".$assoc_uti['fpe'].".jpg","gos"=>$assoc_gos]);
    }
}

output:
#Renderiza o output em json
echo json_encode($output);
?>