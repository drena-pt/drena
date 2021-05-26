<?php
# Composer
require '../vendor/autoload.php';
# Conectar à base de dados
require 'fun.php';

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

$ficheiro = $_FILES['input_video'];
$ficheiro_ext = end(explode(".", $ficheiro['name']));


# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

gerarCodigo:
$codigodobem = gerarCodigo(16);
# Verifica na base de dados se já existe esse código, se sim repete.
if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$codigodobem."'"))){
    goto gerarCodigo;
}


$caminho = "/home/guilha/www/media.drena.xyz/";
$ficheiro_ori_caminho = $caminho."ori/".$codigodobem.".".$ficheiro_ext;

if (!(move_uploaded_file($ficheiro['tmp_name'],$ficheiro_ori_caminho))){
    $erro = "Não foi possivel carregar o ficheiro: ".$ficheiro['name'];
    goto criarJson;
}


$ficheiro_thumb_caminho = $caminho."thumb/".$codigodobem.".jpg";

# Obtem duração do vídeo
$video_duracao = $ffprobe
    ->streams($ficheiro_ori_caminho)
    ->first()
    ->get('duration');

# Gera thumbnail
$video = $ffmpeg->open($ficheiro_ori_caminho);
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($duracao/2))
    ->save($ficheiro_thumb_caminho);

# Processa a thumbnail para o tamanho ideal
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = imagecreatefromjpeg($source_file);
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
resize_crop_image(800, 450, $ficheiro_thumb_caminho, $ficheiro_thumb_caminho, 30);


#Regista o vídeo na base de dados
if ($bd->query("INSERT INTO med (id, uti, nom, tip) VALUES('".$codigodobem."', '".$uti['id']."', '".$ficheiro['name']."', '1');") === FALSE) {
    $erro = "Erro mysqli:".$bd->error;
    goto criarJson;
}


$codec = exec("ffprobe -v error -select_streams v:0 -show_entries stream=codec_name -of default=noprint_wrappers=1:nokey=1 ".$ficheiro_ori_caminho);


criarJson:
$json = array("erro"=>$erro, "codigo"=>$codigodobem, "ext"=>$ficheiro_ext, "codec"=>$codec);
echo json_encode($json);
exit;
?>