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
    if ($uti['ati']!=1){ echo "{'err': 'Utilizador inválido'}"; exit; }
    
    $uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));

    #Verificar se a os códigos coincidem e não são nulos
    if ($uti_mai['cod'] && $cod_mai==$uti_mai['cod']){

        #SQL - Cria um novo código de acesso para o utilizador
		if ($bd->query("INSERT INTO cod (uti, cod) VALUES('".$uti['id']."', uuid());") === TRUE){
            $ultimo_id = $bd->insert_id;
        } else {
            echo '{"err": "'.$bd->error.'"}'; exit;
        }
        #SQL - Gera e altera o código de confirmação do mail por segurança
        if ($bd->query("UPDATE uti_mai SET cod='".substr(md5(uniqid(rand(), true)), 8, 8)."' WHERE id=".$uti_mai['id'].";") === FALSE) {
            echo '{"err": "'.$bd->error.'"}'; exit;
		}

        #SQL - Obtem o ultimo código gerado
        $cod = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM cod WHERE id=".$ultimo_id.";"));
        
        echo '{"cod": "'.$cod['cod'].'"}'; exit; 

    } else {
        echo '{"err": "Código inválido"}'; exit; 
    }

?>