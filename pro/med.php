<?php
require('fun.php');     #Obter funções

$ac = $_GET['ac'];      #Ação

$video = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET['id']."'"));
if ($video OR $uti['adm']==1){ #Se o video existir ou o utilizador for admin
    if ($video['uti']==$uti['id']){ #Se for o dono da média
        $video_id = $video['id'];
        $video_ext = end(explode(".", $video['nom']));
    } else if ($uti['adm']==1) { #Se for admin
        $video_id = $_GET['id'];
        $video_ext = $_GET['ext'];
    } else { #Se não for o dono da média
        echo "Erro: Não és o dono da média.";
        exit;
    }
} else { #Se a média não existir
	echo "Erro: A média não foi encontrada.";
	exit;
}

if ($ac=='eliminar'){
    $pasta="/home/guilha/www/media.drena.xyz/";

    #Original
    $ficheiro_ori = $pasta.'ori/'.$video_id.'.'.$video_ext;
    unlink($ficheiro_ori);

    #Processado
    $ficheiro_webm = $pasta.'webm/'.$video_id.'.webm';
    unlink($ficheiro_webm);

    #Thumb
    $ficheiro_thumb = $pasta.'thumb/'.$video['thu'].'.jpg';
    unlink($ficheiro_thumb);

    #Som
    $ficheiro_som = $pasta.'som/'.$video_id.'.'.$video_ext;
    unlink($ficheiro_som);

    #Imagem
    $ficheiro_img = $pasta.'img/'.$video_id.'.'.$video_ext;
    unlink($ficheiro_img);

    if (file_exists($ficheiro_ori) OR file_exists($ficheiro_webm) OR file_exists($ficheiro_thumb) OR file_exists($ficheiro_som) OR file_exists($ficheiro_img)){
        echo "Erro: Não foi possivel remover os ficheiros.";
    } else if ($bd->query("DELETE FROM med_gos WHERE med='".$video_id."'") === FALSE) {
        echo "Erro: ".$bd->error;
        exit;
    } else if ($bd->query("DELETE FROM med WHERE id='".$video_id."'") === FALSE) {
        echo "Erro: ".$bd->error;
        exit;
    }

    header("Location: /perfil?uti=".$uti['nut']);
    exit;

} else if ($ac=='titulo'){ # Alterar título da média
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