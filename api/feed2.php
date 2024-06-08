<?php
#API - Feed
if ($_POST['tip']=='global'){ #Não é obrigatório autenticação para Feed Global
    $api_noauth=true;
}
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

#Variáveis
$depois = $_POST['depois']; #Apresentar medias depois deste id
$tip = $_POST['tip'];       #Tipo do feed: global (global) ou pessoal ()

$margem = 8;        #Margem de pesquisa (médias)
$limite = 4;        #Limite de objetos (médias ou álbums)
$output = array();  #Output


######### Feed #########
feed:

//Novo depois
if ($depois){
    $depois_med = ($bd->query("SELECT * FROM med WHERE id='".$depois."' LIMIT 1;")->fetch_assoc());
    $depois_med_den = $depois_med['den'];
}


#Feed Pessoal
if ($tip!='global'){
    $sql_conhecidos = "SELECT * FROM ami WHERE a_id='".$uti['id']."' AND sim='1' OR b_id='".$uti['id']."' AND sim='1';";
    $conhecidos = (mysqli_query($bd, $sql_conhecidos)->fetch_assoc());
    
    $lista_conhecidos = $uti['id'];
    if ($conhecidos){
        if ($resultado = $bd->query($sql_conhecidos)){
            while ($campo = $resultado->fetch_assoc()){
                #Adiciona os utilizadores à lista
                if ($campo['a_id']==$uti['id']){
                    $lista_conhecidos .= ','.$campo['b_id'];
                } else {
                    $lista_conhecidos .= ','.$campo['a_id'];
                }
            }
        }
        $feed_pessoal = "AND uti IN (".$lista_conhecidos.") ";//Código extra para limitar o feed caso seja pessoal
    } else {
        $feed_pessoal = "";
    }
} else {
    $feed_pessoal = "";
}



#Query de media ordenada por data, a partir da mais recente
if (!$depois){
    $sql_media = "SELECT * 
    FROM med 
    WHERE pri = 0 
    $feed_pessoal 
    ORDER by den DESC, id DESC
    LIMIT $margem";
    
#Query de media ordenada por data, a partir do $depois
} else {
    $sql_media = "SELECT * 
    FROM med 
    WHERE ((den = '$depois_med_den' AND id < '$depois' AND pri = 0)
        OR (den < '$depois_med_den' AND pri = 0)) 
    $feed_pessoal 
    ORDER BY den DESC, id DESC
    LIMIT $margem;";
}

######### Feed ######FIM


if ($res = $bd->query($sql_media)) {
    while ($med = $res->fetch_assoc()) {

        #Atual
        $depois = $med['id'];

        #Se a média estiver num álbum
        if ($med['alb']){
            
            #SQL: Obtém a média mais recente do álbum
            $alb_ultima_med = ($bd->query("SELECT * FROM med WHERE alb='".$med['alb']."' ORDER BY den DESC LIMIT 1;")->fetch_assoc());

            #Se a média for a mais recento do álbum, enviar no output
            if ($alb_ultima_med['id']==$med['id']){

                #OUTPUT: Um álbum
                $output[] = ["obj_tip"=>"alb","id"=>$med['alb']];

            #Se não for a média mais recente, dá skip
            } else {
                continue;
            }

        #Se a média não estiver num álbum
        } else {

            #SQL: Obtém os dados do utilizador dono da média
            $med_uti = ($bd->query("SELECT * FROM uti WHERE id='".$med['uti']."'")->fetch_assoc());
            #SQL: Obtém o gosto do utilizador logado (0 ou 1)
            $med_meu_gos = mysqli_num_rows($bd->query("SELECT * FROM med_gos WHERE med='".$med['id']."' AND uti='".$uti['id']."'"));
            #SQL: Obtém todos os comentários da média
            $med_com = array();
            if ($r_med_com = $bd->query("SELECT * FROM med_com WHERE med='".$med['id']."'")) {
                while ($com = $r_med_com->fetch_assoc()) {
                    $com_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$com["uti"]."'"));
                    $com['uti'] = $com_uti['nut'];
                    $com['uti_fpe'] = $url_media.'fpe/'.$com_uti['fpe'].'.jpg';
                    $med_com[] = $com;
                }
            }

            #Adiciona variáveis à med[]
            $med['obj_tip'] = "med";    #Define no output final o tipo de objeto
            $med['uti'] = ["nut"=>$med_uti['nut'],"fpe"=>$url_media."fpe/".$med_uti['fpe'].".jpg"];
            $med['meu_gos'] = $med_meu_gos;
            $med['com'] = $med_com;

            #OUTPUT: Uma média
            $output[] = $med;

        }

        //Menos um objeto necessário
        --$limite;

        //Se já existem objetos suficientes: para o while
        if ($limite<=0){
            break;
        }
    }

    //Se não houverem objetos suficientes: renicia
    if ($limite>0){
        --$limite;
        goto feed;
    }

}

output:
if ($output!=null){#Se for nulo, significa que é a ultima media de todas
    #OUTPUT: Última média
    $output[] = ["obj_tip"=>"depois","med"=>$depois];
    #Renderiza o output em json
    echo json_encode($output);
}
exit;
?>