<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
#Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
include_once('bd.php');

#Código do mail do utilizador
$cod_mai = $_GET['cod'];

#Utilizador
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET['uti']."'"));

#Verificar se a conta está ativa
if ($uti['ati']!=1){ echo "{'err': 'Utilizador inválido'}"; exit; }

#Mail do utilizador
$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));

#Verificar se a os códigos coincidem e não são nulos
if ($uti_mai['cod'] && $cod_mai==$uti_mai['cod']){
    
    #Obtem ação
    $ac = $_GET['ac'];

    if ($ac=='eliminar'){ #Se a ação for eliminar o comentário

        $com = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_com WHERE id='".$_GET["id"]."';")); #Informações do comentário
        if ($com AND $com['uti']==$uti['id']){ #Se o comentário existir e o utilizador for o dono
            #Apaga o comentário da base de dados
            if ($bd->query("DELETE FROM med_com WHERE id='".$com['id']."'") === FALSE) {
                echo '{"err": "Erro mysqli: '.$bd->error.'}'; exit;
            } else {
                echo '{"est": "sucesso"}'; exit;
            }
        } else {
            echo "{'err': 'O comentário é inválido'}"; exit;
        }
    } else if ($ac=='criar'){ #Se a ação criar um comentário

        $med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["med"]."';")); #Informações da media
        if ($med){ #Se a media existir
            $com = $_GET['com'];
            if ($com){ # Se o texto de comentário não for nulo

                #SQL - Regista o comentário na base de dados
                if ($bd->query("INSERT INTO med_com (uti, med, tex) VALUES('".$uti['id']."', '".$med["id"]."', '".addslashes($com)."');") === TRUE){
                    $ultimo_id = $bd->insert_id;
                    echo '{"id": "'.$ultimo_id.'"}'; exit;
                } else {
                    echo '{"err": "'.$bd->error.'"}'; exit;
                }

            } else {
                echo "{'err': 'O comentário não pode ser nulo'}"; exit;
            }
        } else {
            echo "{'err': 'A Media não existe'}"; exit;
        }
    } else {
        echo '{"err": "Ação inválida"}'; exit; 
    }
} else {
    echo '{"err": "Código inválido"}'; exit; 
}

?>