<?php
#API - Média (Comentários)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');
#Função de Notificações
require('../pro/not.php');

#Obtem ação
$ac = $_POST['ac'];

if ($ac=='eliminar'){ #Se a ação for eliminar o comentário

    $com = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_com WHERE id='".$_POST["id"]."';")); #Informações do comentário
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

    $med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST["med"]."';")); #Informações da media
    if ($med){ #Se a media existir
        $com = $_POST['com'];
        if ($com){ # Se o texto de comentário não for nulo

        #SQL - Regista o comentário na base de dados
        if ($bd->query("INSERT INTO med_com (uti, med, tex) VALUES('".$uti['id']."', '".$med["id"]."', '".addslashes($com)."');") === TRUE){
            $ultimo_id = $bd->insert_id;
			notificacao($uti['id'],$med['uti'],'com',$med['id'],$ultimo_id);
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
?>