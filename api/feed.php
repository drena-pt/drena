<?php
#API - Feed
if ($_POST['tip']=='global'){ #Não é obrigatório autenticação para Feed Global
    $api_noauth=true;
}
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$output = array();

$depois = $_POST['depois'];    #Apresentar media depois do id defenido

#Feed Global
if ($_POST['tip']=='global'){

    #Query de media ordenada por data limite 4
    $sql_media = "select t.*
    from med t cross join (select * from med where id = '".$depois."') c
    where t.den < c.den or (t.den = c.den and t.id >= c.id) and t.pri=0
    order by t.den desc, t.id
    limit 1,4";

    if (!$depois OR !mysqli_num_rows($bd->query($sql_media))){
        $sql_media = "SELECT * FROM med where pri=0 ORDER by den DESC LIMIT 3";
    }

#Feed Pessoal
} else {
    $sql_conhecidos = "SELECT * FROM ami WHERE a_id='".$uti['id']."' AND sim='1' OR b_id='".$uti['id']."' AND sim='1' ORDER by b_dat DESC";
    $conhecidos = (mysqli_query($bd, $sql_conhecidos)->fetch_assoc());
    
    $lista_feed = $uti['id'];
    if ($conhecidos){
        if ($resultado = $bd->query($sql_conhecidos)){
            while ($campo = $resultado->fetch_assoc()){
                #Adiciona os utilizadores à lista
                if ($campo['a_id']==$uti['id']){
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
        $output_erro = "uti(".$uti['nut'].") não tem amigos.";
        $output[] = array("erro"=>$output_erro);
        goto output;
    }
}

if ($resultado = $bd->query($sql_media)) {
    while ($med = $resultado->fetch_assoc()) {
        #Obtém os dados do utilizador dono da média
        $med_uti = ($bd->query("SELECT * FROM uti WHERE id='".$med['uti']."'")->fetch_assoc());
        #Obtém o gosto do utilizador logado (0 ou 1)
        $med_gos = mysqli_num_rows($bd->query("SELECT * FROM med_gos WHERE med='".$med['id']."' AND uti='".$uti['id']."'"));
        
        $med_com = array();
        #SQL: Obtém todos os comentários da média
        if ($r_med_com = $bd->query("SELECT * FROM med_com WHERE med='".$med['id']."'")) {
            while ($com = $r_med_com->fetch_assoc()) {
                $com_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$com["uti"]."'"));
                $com['uti'] = $com_uti['nut'];
                $com['uti_fpe'] = $url_media.'fpe/'.$com_uti['fpe'].'.jpg';
                $med_com[] = $com;
            }
        }

        #######Mudar como isto está organizado, tirar o gos dentro do uti.
        $output[] = array("med"=>$med,"uti"=>["nut"=>$med_uti['nut'],"fpe"=>$url_media."fpe/".$med_uti['fpe'].".jpg"],"gos"=>$med_gos,"com"=>$med_com);
    }
}

output:
#Renderiza o output em json
echo json_encode($output);
?>