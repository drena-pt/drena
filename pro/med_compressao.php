<?php
#Processo - Comprimir média
#Funções
require_once(__DIR__."/fun.php");
#Composer
require_once(__DIR__."/../vendor/autoload.php");
#Função de Notificações
require(__DIR__."/not.php");

# Obtem as informações da média na base de dados; $argv são as variáveis passadas pelo comando exec.
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$argv[1]."';"));

// Se a média existir
if ($med){
    # Obtem as informações do utilizador
    $med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."';"));

    if ($med['tip']=='1'){ # Se a media for um vídeo

        # Carrega a biblioteca PHPFFMpeg
        $ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout'          => 36000
        ));
        $ffprobe = FFMpeg\FFProbe::create(array(
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout'          => 36000
        ));

        #Obtem o ficheiro original e descobre a extensão
		$med_file = basename(glob($dir_media."ori/".$med['id']."*")[0]);
        $caminho_ori = $dir_media."ori/".$med_file; # Caminho do vídeo original
        $caminho_comprimido = $dir_media."comp/".$med['id'].".mp4"; # Caminho do vídeo comprimido
        $caminho_convertido = $dir_media."conv/".$med['id'].".mp4"; # Caminho do vídeo comprimido

        if ($med['est']=='1'){ # Se o estado for 1. (Tem bitrate alto e não têm compressão)
            
            if (file_exists($caminho_comprimido)){
                echo "Erro: O vídeo já foi comprimido";
                exit;
            }

            $max_bitrate = 7996544;
            # Obtem o bitrate do vídeo
            $bitrate = $ffprobe
                ->streams($caminho_ori)
                ->videos()
                ->first() 
                ->get('bit_rate');

            if ($bitrate>=$max_bitrate){ # Se o bitrate realmente for alto
                
                # Obtem as dimensões do vídeo
                $video_dimensoes = $ffprobe
                    ->streams($caminho_ori)
                    ->videos()
                    ->first()
                    ->getDimensions();
                $ori_height = $video_dimensoes->getHeight();
                $ori_width = $video_dimensoes->getWidth();
            
                $novo_height = 720; # Altura máxima

                if ($ori_height<=$novo_height){ # Se a altura original for mais pequena ou igual à máxima mantem
                    $novo_height = $ori_height;
                    $novo_width = $ori_width;
                } else {
                    $numero_magico = $ori_height/$novo_height; # Diferença entre as alturas
                    $novo_width = round($ori_width/$numero_magico);
                }

                # Muda o estado na base de dados para 2 (em processo)
                if ($bd->query("UPDATE med SET est='2' WHERE id='".$med["id"]."';") === FALSE) {
                    echo "Erro mysqli: ".$bd->error;
                    exit;
                }

                $video = $ffmpeg->open($caminho_ori); # Carrega o vídeo no ffmpeg para futuras ações.

                # Renderiza o vídeo em X264
                $video->filters()->resize(new FFMpeg\Coordinate\Dimension($novo_width, $novo_height));
                $format = new FFMpeg\Format\Video\X264('libmp3lame','libx264');
                $video
                    ->save($format, $caminho_comprimido);

                if (file_exists($caminho_comprimido)){
                    # Muda o estado na base de dados para 3 (comprimido)
                    if ($bd->query("UPDATE med SET est='3' WHERE id='".$med["id"]."';") === FALSE) {
                        echo "Erro mysqli: ".$bd->error;
                    } else {
                        echo "Vídeo comprimido com sucesso!";
                        notificacao($med_uti['id'],$med_uti['id'],'processado',$med['id']);
                    }
                } else {
                    echo "Erro: O vídeo não foi comprimido.";
                }
            } else {
                echo "Erro: O servidor mentiu ao definir o estado do vídeo, este erro não deveria acontecer.";
            }
        } else if ($med['est']=='4'){ # Se o estado for 4. (Codec não suportado)

            if (file_exists($caminho_convertido)){
                echo "Erro: O vídeo já foi convertido";
                exit;
            }

            # Obtem o codec do vídeo
            $codec = $ffprobe
                ->streams($caminho_ori)
                ->videos()
                ->first() 
                ->get('codec_name');

            if ($codec=='hevc'){ # Se o codec realmente não for suportado
                
                # Muda o estado na base de dados para 2 (em processo)
                if ($bd->query("UPDATE med SET est='2' WHERE id='".$med["id"]."';") === FALSE) {
                    echo "Erro mysqli: ".$bd->error;
                    exit;
                }

                $max_bitrate = 8192000;
                # Obtem o bitrate do vídeo
                $bitrate_ori = $ffprobe
                    ->streams($caminho_ori)
                    ->videos()
                    ->first() 
                    ->get('bit_rate');

                # Define o bitrate do vídeo
                if ($bitrate_ori>$max_bitrate){
                    $bitrate = $max_bitrate;
                } else {
                    $bitrate = $bitrate_ori;
                }

                $video = $ffmpeg->open($caminho_ori); # Carrega o vídeo no ffmpeg para futuras ações.

                # Renderiza o vídeo em X264
                $format = new FFMpeg\Format\Video\X264('libmp3lame','libx264');
                $format
                    ->setKiloBitrate(substr($bitrate,0,-3));
                $video
                    ->save($format, $caminho_convertido);

                if (file_exists($caminho_convertido)){
                    # Muda o estado na base de dados para 5 (convertido)
                    if ($bd->query("UPDATE med SET est='5' WHERE id='".$med["id"]."';") === FALSE) {
                        echo "Erro mysqli: ".$bd->error;
                    } else {
                        echo "Vídeo convertido com sucesso!";
                        notificacao($med_uti['id'],$med_uti['id'],'processado',$med['id']);
                    }
                } else {
                    echo "Erro: O vídeo não foi convertido.";
                }
            } else {
                echo "Erro: O servidor mentiu ao definir o estado do vídeo, este erro não deveria acontecer.";
            }

        } else {
            echo "Erro: Não foi possivel executar uma tarefa para o estado: ".$med['est'];
        }
    } else {
        echo "Erro: Média inválida.";
    }
}
exit;
?>