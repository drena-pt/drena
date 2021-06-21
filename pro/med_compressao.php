<?php
# Composer
require '/home/guilha/www/drena.xyz/vendor/autoload.php';
# Conectar à base de dados
require '/home/guilha/www/drena.xyz/pro/fun.php';

# Obtem as informações da média na base de dados; $argv são as variáveis passadas pelo comando exec.
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$argv[1]."';"));

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

    $caminho = "/home/guilha/www/media.drena.xyz/";
    $med_ext = end(explode(".", $med['nom'])); # Extensão do vídeo
    $caminho_ori = $caminho."ori/".$med['id'].".".$med_ext; # Caminho do vídeo original
    $caminho_webm = $caminho."webm/".$med['id'].".webm"; # Caminho do vídeo comprimido

    if ($med['est']=='1'){ # Se o estado for 1. (Tem bitrate alto e não têm compressão)
        
        if (file_exists($caminho_webm)){
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

            # Renderiza o vídeo em WebM
            $video->filters()->resize(new FFMpeg\Coordinate\Dimension($novo_width, $novo_height));
            $format = new FFMpeg\Format\Video\WebM();
            #$format
            #    ->setKiloBitrate(1280);
            $video
                ->save($format, $caminho_webm);

            if (file_exists($caminho_webm)){
                # Muda o estado na base de dados para 3 (comprimido)
                if ($bd->query("UPDATE med SET est='3' WHERE id='".$med["id"]."';") === FALSE) {
                    echo "Erro mysqli: ".$bd->error;
                } else {
                    echo "Vídeo comprimido com sucesso!";
                }
            } else {
                echo "Erro: O vídeo não foi comprimido.";
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
exit;
?>