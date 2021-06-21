<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
# Composer
require '../vendor/autoload.php';
# Conectar à base de dados
require 'fun.php';

if ($uti['adm']!='1'){
    echo "boi baza";
    exit;
}

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

$max_bitrate = 7996544;


function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

$caminho = "/home/guilha/www/media.drena.xyz/ori/";

$bolsonaro = array();

$pesquisa = "SELECT * FROM med WHERE tip='1';";
if ($resultado = $bd->query($pesquisa)) {
    while ($campo = $resultado->fetch_assoc()) {

        $ficheiro_caminho = $caminho."".$campo['id'].".".end(explode(".", $campo['nom']));

        $bitrate = $ffprobe
            ->streams($ficheiro_caminho)
            ->videos()
            ->first() 
            ->get('bit_rate');
        $codec = $ffprobe
            ->streams($ficheiro_caminho)
            ->videos()
            ->first() 
            ->get('codec_name');
            
        if ($bitrate>=$max_bitrate AND $campo['est']==0){
            $bd->query("UPDATE med SET est=1 WHERE id='".$campo["id"]."';");
        }

        $bolsonaro[] = array('bit'=>$bitrate,'id'=>$campo['id'],'est'=>$campo['est']);

    }

}
arsort($bolsonaro);

foreach($bolsonaro as $result) {
    echo $result['est']." - ".formatSizeUnits($result['bit'])." - ".$result['id'].'<br>';
} 
echo "done";
?>