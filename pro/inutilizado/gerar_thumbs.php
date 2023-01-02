<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require '../fun.php'; #Funções
require '../../vendor/autoload.php'; #Composer

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

if ($uti['car']!='1'){
    echo "boi baza";
    exit;
}

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

# Função para gerar thumbnail
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = imagecreatefromstring(file_get_contents($source_file));
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    if($width_new > $width){
        $h_point = (($height - $height_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
    imagejpeg($dst_img, $dst_dir, $quality);
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}

$pesquisa = "SELECT * FROM med WHERE thu IS NULL ORDER by den ASC";
if ($resultado = $bd->query($pesquisa)) {
    while ($campo = $resultado->fetch_assoc()) {
        if ($campo['tip']=='1' OR $campo['tip']=='3'){
            echo $campo['nom']."<br>";

            gerarCodigoThumb:
            $codigoThumb = gerarCodigo(16);
            # Verifica na base de dados se já existe esse código, se sim repete.
            if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_thu WHERE id='".$codigoThumb."'"))){
                goto gerarCodigoThumb;
            }
            
            $extee = end(explode(".", $campo['nom']));

            $caminhoThumb = $dir_media."thumb/".$codigoThumb.".jpg";

            # Processa a thumbnail para o tamanho ideal
            if ($campo['tip']=='1'){
                $caminhoMedia = $dir_media."ori/".$campo['id'].".".$extee;

                # Obtem duração do vídeo
                $video_duracao = $ffprobe
                    ->streams($caminhoMedia)
                    ->first()
                    ->get('duration');

                # Gera thumbnail
                $video = $ffmpeg->open($caminhoMedia);
                $video
                    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($video_duracao/2))
                    ->save($caminhoThumb);

                resize_crop_image(800, 450, $caminhoThumb, $caminhoThumb, 30);
            } else if ($campo['tip']=='3'){
                $caminhoMedia = $dir_media."img/".$campo['id'].".".$extee;
                resize_crop_image(640, 480, $caminhoMedia, $caminhoThumb, 30);
            }

            $bd->query("UPDATE med SET thu='".$codigoThumb."' WHERE id='".$campo["id"]."';");

            if ($bd->query("INSERT INTO med_thu (id, med) VALUES('".$codigoThumb."', '".$campo["id"]."');") === FALSE) {
                $erro = "Erro mysqli:".$bd->error;
                exit;
            }
            
        }
    }

}
*/
?>