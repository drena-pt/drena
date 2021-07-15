<?php
require('fun.php'); # Obter funções

$ac = $_GET['ac'];  # Ação
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET['id']."'"));

if ($med){ # Se a média existir

    $med_ext = end(explode(".",$med['nom'])); # Extensão do ficheiro

    if ($med['uti']==$uti['id']){ # Se for o dono da média
        
        if ($ac=='eliminar'){ # Se a ação for eliminar
        
            if ($med['est']!='2'){ # Se o estado da média não for 2 (processando)
                $caminho = "/home/guilha/www/media.drena.xyz/";
    
                $caminho_ori = $caminho.'ori/'.$med['id'].'.'.$med_ext; # Original
                unlink($caminho_ori);
            
                $caminho_som = $caminho.'som/'.$med['id'].'.'.$med_ext; # Som
                unlink($caminho_som);
            
                $caminho_img = $caminho.'img/'.$med['id'].'.'.$med_ext; # Imagem
                unlink($caminho_img);
    
                $caminho_comprimido = $caminho.'comp/'.$med['id'].'.mp4';    # Comprimido
                unlink($caminho_comprimido);

                $caminho_convertido = $caminho.'conv/'.$med['id'].'.mp4'; # Convertido
                unlink($caminho_convertido);
            
                $caminho_thumb = $caminho.'thumb/'.$med['thu'].'.jpg';  # Thumb
                unlink($caminho_thumb);
    
                # Se existir algum dos ficheiros que supostamente foram apagados
                if (file_exists($caminho_ori) OR file_exists($caminho_som) OR file_exists($caminho_img) OR file_exists($caminho_comprimido) OR file_exists($caminho_convertido) OR file_exists($caminho_thumb)){
                    echo "Erro: Não foi possivel remover os ficheiros.";
                } else if ($bd->query("DELETE FROM med_com WHERE med='".$med['id']."'") === FALSE) {
                    echo "Erro mysql: ".$bd->error;
                } else if ($bd->query("DELETE FROM med_gos WHERE med='".$med['id']."'") === FALSE) {
                    echo "Erro mysql: ".$bd->error;
                } else if ($bd->query("DELETE FROM med WHERE id='".$med['id']."'") === FALSE) {
                    echo "Erro mysql: ".$bd->error;
                } else {
                    header("Location: /../perfil?uti=".$uti['nut']);
                }
            } else {
                echo "Erro: Não podes eliminar a média enquanto está a ser processada.";
            }
            exit;
        
        } else if ($ac=='titulo'){ # Alterar título da média
            if ($_POST['tit']){
                if ($bd->query("UPDATE med SET tit='".addslashes($_POST['tit'])."' WHERE id='".$med['id']."'") === FALSE) {
                    echo "Erro:".$bd->error;
                    exit;
                }
            }
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit;

        } else if ($ac=='privar'){ # Alterar privacidade
            if ($med['pri']=='1'){
                if ($bd->query("UPDATE med SET pri='0' WHERE id='".$med['id']."'") === FALSE) {
                    echo "Erro:".$bd->error;
                    exit;
                }
            } else {
                if ($bd->query("UPDATE med SET pri='1' WHERE id='".$med['id']."'") === FALSE) {
                    echo "Erro:".$bd->error;
                    exit;
                }
            }
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit;

        } else if ($ac=='comprimir'){ # Alterar título da média
            
            if ($med['tip']=='1' AND $med['est']=='1'){ # Se a media for um vídeo e o estado for 1 (bitrate alto)
                exec("php /home/guilha/www/drena.xyz/pro/med_compressao.php ".$med['id']." > /dev/null &");
                sleep(2);
                header("Location: ".$_SERVER['HTTP_REFERER']);
            } else {
                echo "Erro: Média inválida.";
            }
            exit;

        } else {
            echo "Erro: Nenhuma ação selecionada.";
            exit;
        }

    } else { # Se não for o dono da média
        echo "Erro: Não és o dono da média.";
    }

} else { #Se a média não existir
	echo "Erro: A média não foi encontrada.";
}

exit;
?>