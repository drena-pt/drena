<?php
    /* error_reporting(E_ALL);
    ini_set('display_errors', 'On'); */

    #Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    include_once('bd.php');

    #Verifica se a media existe
    $query = $bd->query("SELECT * FROM med WHERE id='".$_GET['id']."'");

    #Se a média existir
    if (mysqli_num_rows($query)){

        #Obtem dados da média
        $query_assoc = mysqli_fetch_assoc($query);

        #Erro caso média privada
        if ($query_assoc['pri']==1){

            $output_erro = "id(".$_GET['id'].") é privada.";
            $output = array("erro"=>$output_erro);

        } else {

            #Obtem dados do utilizador dono da média
            $query_uti = $bd->query("SELECT * FROM uti WHERE id='".$query_assoc['uti']."'");
            $assoc_uti = mysqli_fetch_assoc($query_uti);

            $output = array("med"=>$query_assoc,"uti"=>["nut"=>$assoc_uti['nut']]);
            
        }

    #Se a média não existir
    } else {
        $output_erro = "id(".$_GET['id'].") não existe.";
        $output = array("erro"=>$output_erro);
    }
    
    #Renderiza o output em json
    echo json_encode($output);
?>