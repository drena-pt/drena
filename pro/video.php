<?php
require('fun.php');     #Obter funções

$ac = $_GET['ac'];      #Ação

$video = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET['id']."'"));
if ($video OR $uti['adm']==1){ #Se o video existir ou o utilizador for admin
    if ($video['uti']==$uti['id']){ #Se for o dono do video
        $video_id = $video['id'];
        $video_ext = end(explode(".", $video['nom']));
    } else if ($uti['adm']==1) { #Se for admin
        $video_id = $_GET['id'];
        $video_ext = $_GET['ext'];
    } else { #Se não for o dono do video
        echo "Erro: Não és o dono do vídeo.";
        exit;
    }
} else { #Se o vídeo não existir
	echo "Erro: O vídeo não foi encontrado.";
	exit;
}

if ($ac=='eliminar'){
    $pasta='/home/guilha/www/media.drena.xyz/';

    $ori = $pasta.'ori/'.$video_id.'.'.$video_ext;
    unlink($ori);               #Original
    $webm = $pasta.'webm/'.$video_id.'.webm';
    unlink($webm);              #Processado
    $thumb = $pasta.'thumb/'.$video_id.'.jpg';
    unlink($thumb);             #Thumb

    $som = $pasta.'som/'.$video_id.'.'.$video_ext;
    unlink($som);               #Som

    echo "<br>".$ori;
    echo "<br>".$webm;
    echo "<br>".$thumb;
    echo "<br>".$som;

    if (file_exists($ori) OR file_exists($webm) OR file_exists($thumb) OR file_exists($som)){
        echo "Erro: Não foi possivel remover os ficheiros.";
    } else if ($bd->query("DELETE FROM med_gos WHERE med='".$video_id."'") === FALSE) {
        echo "Erro:".$bd->error;
        exit;
    } else if ($bd->query("DELETE FROM med WHERE id='".$video_id."'") === FALSE) {
        echo "Erro:".$bd->error;
        exit;
    }

    header("Location: /");
    exit;

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