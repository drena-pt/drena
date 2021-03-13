<?php
require('fun.php');     #Obter funções

$ac = $_GET['ac'];      #Ação

$video = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET['id']."'"));
if (!$video){           #Se o vídeo não existir
	echo"Erro: O vídeo não foi encontrado.";
	exit;
}

/* $video = array(
    "id"  => $_GET['id'],
    "ext" => "mp4"
); */

if ($ac=='eliminar'){
    if ($uti['adm']==0){    #Se o utilizador for administrador
        echo"Erro: Não és administrador.";
        exit;
    }
    $pasta='/home/root/media.drena.xyz';

    $ori = $pasta.'/ori/'.$video['id'].'.'.$video['ext'];
    unlink($ori);               #Original
    $webm = $pasta.'/webm/'.$video['id'].'.webm';
    unlink($webm);              #Processado
    $thumb = $pasta.'/thumb/'.$video['id'].'.jpg';
    unlink($thumb);             #Thumb

    if (file_exists($ori) OR file_exists($webm) OR file_exists($thumb)){
        echo "Erro: Não foi possivel remover os ficheiros.";
    } else if ($bd->query("DELETE FROM med_gos WHERE med='".$video['id']."'") === FALSE) {
        echo "Erro:".$bd->error;
        exit;
    } else if ($bd->query("DELETE FROM med WHERE id='".$video['id']."'") === FALSE) {
        echo "Erro:".$bd->error;
        exit;
    }
} else if ($ac=='titulo'){
    if ($video['uti']==$uti['id']){
        if ($_POST['tit']){
            if ($bd->query("UPDATE med SET tit='".$_POST['tit']."' WHERE id='".$_GET['id']."'") === FALSE) {
                echo "Erro:".$bd->error;
            }
        }
    }
}
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>