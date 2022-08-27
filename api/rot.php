<?php
    /* error_reporting(E_ALL);
    ini_set('display_errors', 'On'); */

    #Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    include_once('bd.php');

    $cod_mai = $_GET['cod'];    #Código do mail do utilizador
    
    $uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET['uti']."'"));
    
    #Verificar se a conta está ativa
    if ($uti['ati']!=1){ echo '{"err": "Utilizador inválido"}'; exit; }
    
    $uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));

    #Verificar se a os códigos coincidem e não são nulos
    if ($uti_mai['cod'] && $cod_mai==$uti_mai['cod']){

        #Título não pode ser nulo
        if ($_GET['tit']){

            $default_rot = "[{\"type\":\"screenbox general\",\"children\":[{\"text\": \"Começa por aqui...\"}]}]";

            #SQL - Cria o novo roteiro
            if ($bd->query("INSERT INTO rot (id, uti, tit, rot) VALUES(uuid(), '".$uti['id']."', '".$_GET['tit']."', '".$default_rot."');") === TRUE){
                #SQL - Obtem o id do último roteiro criado
                $ultimo_rot = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM rot ORDER BY dcr DESC LIMIT 1"));
                echo '{"rot": "'.$ultimo_rot['id'].'"}'; exit;
            } else {
                echo '{"err": "'.$bd->error.'"}'; exit;
            }
            #SQL - Gera um novo código de confirmação do mail (por segurança)
            if ($bd->query("UPDATE uti_mai SET cod='".substr(md5(uniqid(rand(), true)), 8, 8)."' WHERE id=".$uti_mai['id'].";") === FALSE) {
                echo '{"err": "'.$bd->error.'"}'; exit;
            }
            echo '{"rot": "'.$ultimo_id.'"}'; exit;

        } else {
            echo '{"err": "Título nulo"}'; exit; 
        }

    } else {
        echo '{"err": "Código inválido"}'; exit; 
    }

?>