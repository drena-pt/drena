<?php
#API - Médias (Adiciona/Remove médias e Cria albúns)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

$ac = $_POST['ac']; #Ação

#Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

if ($_POST['alb']){
    #Informações do albúm
    $alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$_POST['alb']."'"));
    #Se o albúm não existir e o utilizador não for dono
    if (!$alb AND $alb['uti']!=$uti['id']){
        echo '{"err": "Albúm inválido."}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }
}

if ($_POST['med']){
    #Informações da media
    $med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST['med']."'"));
    #Se a média não existir e o utilizador não for dono
    if (!$med AND $med['uti']!=$uti['id']){
        echo '{"err": "Média inválida."}';
        header('HTTP/1.1 400 Bad Request'); exit;
    }
}

#AÇÃO: Adicionar/Remover médias do albúm
if ($ac=='med' AND $med AND $alb){
    
    #Remove do albúm, se já estiver
    if ($med['alb']==$alb['id']){
        if (mysqli_query($bd, "UPDATE med SET alb=NULL WHERE id='".$med['id']."';")){
            echo '{"est": "false"}';
        } else {
            echo '{"err": "'.$bd->error.'"}';
        }
    #Adiciona ao albúm, se não estiver
    } else {
        if (mysqli_query($bd, "UPDATE med SET alb='".$alb['id']."' WHERE id='".$med['id']."';")){
            echo '{"est": "true"}';
        } else {
            echo '{"err": "'.$bd->error.'"}';
        }
    }

#AÇÃO: Alterar o título do albúm
} else if ($ac=='tit' AND $alb){

    if ($_POST['tit']){
        if ($bd->query("UPDATE med_alb SET tit='".$_POST['tit']."' WHERE id='".$alb['id']."'") === FALSE) {
            echo '{"err": "'.$bd->error.'"}'; exit;
        }
    }
    echo '{"est": "sucesso"}';

#AÇÃO: Eliminar albúm
} else if ($ac=='eliminar' AND $alb){

    #Desassocia toda a média do álbum
	if ($bd->query("UPDATE med SET alb=NULL WHERE alb='".$alb['id']."'") === FALSE) {
        echo '{"err": "'.$bd->error.'"}'; exit;
    }
	#Elimina o álbum
	if ($bd->query("DELETE FROM med_alb WHERE id='".$alb['id']."'") === FALSE) {
        echo '{"err": "'.$bd->error.'"}'; exit;
	}
    echo '{"est": "eliminado"}';

#AÇÃO: Criar albúm
} else if ($ac=='criar' AND $med){

	#Gera código unico
	gerarCodigo:
	$codigo = gerarCodigo(6);
	#Verifica na base de dados se já existe esse código, se sim repete.
	if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$codigo."'"))){
		goto gerarCodigo;
	}

    #Cria um novo álbum
    if (mysqli_query($bd, "INSERT INTO med_alb (id, uti, thu) VALUES ('".$codigo."', '".$uti['id']."', '".$med['thu']."');")){

        #Adiciona a média ao album
        if (mysqli_query($bd, "UPDATE med SET alb='".$codigo."' WHERE id='".$med["id"]."';")){
            echo '{"est": "sucesso", "alb": "'.$codigo.'"}';
        } else {
            echo '{"err": "'.$bd->error.'"}';
        }
    } else {
        echo '{"err": "'.$bd->error.'"}';
    }

#AÇÃO INVÁLIDA
} else {
    echo '{"err": "Ação inválida"}';
    header('HTTP/1.1 400 Bad Request');
}

exit;
?>