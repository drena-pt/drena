<?php
    /* error_reporting(E_ALL);
    ini_set('display_errors', 'On'); */

    #Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    include_once('bd.php');

    $output = array();

    if ($_GET['tip']=='global'){
        if ($resultado = $bd->query("SELECT * FROM med ORDER by den DESC")) {
            while ($med = $resultado->fetch_assoc()){
                #Obtem dados do utilizador dono da média
                $assoc_uti = ($bd->query("SELECT * FROM uti WHERE id='".$med['uti']."'")->fetch_assoc());
                $output[] = array("med"=>$med,"uti"=>["nut"=>$assoc_uti['nut'],"fot"=>$assoc_uti['fot']]);
            }
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
    
            if ($resultado = $bd->query("SELECT * FROM med WHERE uti IN (".$lista_feed.") ORDER by den DESC")) {
                while ($med = $resultado->fetch_assoc()) {
        
                    #Obtem dados do utilizador dono da média
                    $assoc_uti = ($bd->query("SELECT * FROM uti WHERE id='".$med['uti']."'")->fetch_assoc());
                    $output[] = array("med"=>$med,"uti"=>["nut"=>$assoc_uti['nut'],"fot"=>$assoc_uti['fot']]);
        
                }
            }
    
        } else {
            $output_erro = "uti(".$_GET['id'].") não tem amigos.";
            $output[] = array("erro"=>$output_erro);
        }
    }
    
    #Renderiza o output em json
    echo json_encode($output);
?>