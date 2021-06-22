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

$caminho = "/home/guilha/www/media.drena.xyz/";

# Gera código unico para media
gerarCodigo:
$codigoMedia = gerarCodigo(16);
# Verifica na base de dados se já existe esse código, se sim repete.
if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$codigoMedia."'"))){
    goto gerarCodigo;
}

$ficheiro_ori_caminho = $caminho."ori/".$codigoMedia.".".$ficheiro_ext;

if (!(move_uploaded_file($ficheiro['tmp_name'],$ficheiro_ori_caminho))){
    $erro = "Não foi possivel carregar o ficheiro: ".$ficheiro['name'];
    goto criarJson;
}

# Gera código unico para a thumb
gerarCodigoThumb:
$codigoThumb = gerarCodigo(16);
# Verifica na base de dados se já existe esse código, se sim repete.
if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_thu WHERE id='".$codigoThumb."'"))){
    goto gerarCodigoThumb;
}

$ficheiro_thumb_caminho = $caminho."thumb/".$codigoThumb.".jpg";

$max_bitrate = 7996544;
# Obtem o bitrate do vídeo
$bitrate = $ffprobe
    ->streams($ficheiro_ori_caminho)
    ->videos()
    ->first() 
    ->get('bit_rate');

if ($bitrate>=$max_bitrate){
    $estado = 1;
} else {
    $estado = 0;
}

# Obtem o codec do vídeo
$codec = $ffprobe
    ->streams($ficheiro_ori_caminho)
    ->videos()
    ->first() 
    ->get('codec_name');

if ($codec=='hevc'){
    $estado = 4;    # Codec não suportado para web
}

# Obtem duração do vídeo
$video_duracao = $ffprobe
    ->streams($ficheiro_ori_caminho)
    ->first()
    ->get('duration');

# Gera thumbnail
$video = $ffmpeg->open($ficheiro_ori_caminho);
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($video_duracao/2))
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

# Regista a thumbnail na base de dados
if ($bd->query("INSERT INTO med_thu (id, med) VALUES('".$codigoThumb."', '".$codigoMedia."');") === FALSE) {
    $erro = "Erro mysqli:".$bd->error;
    goto criarJson;
}

# Regista o vídeo na base de dados
if ($bd->query("INSERT INTO med (id, uti, nom, tip, est, thu) VALUES('".$codigoMedia."', '".$uti['id']."', '".$ficheiro['name']."', '1', '".$estado."', '".$codigoThumb."');") === FALSE) {
    $erro = "Erro mysqli:".$bd->error;
    goto criarJson;
}

if ($estado==4){ # Converte o vídeo para WebM se o codec não for suportado para web
    exec("php /home/guilha/www/drena.xyz/pro/med_compressao.php ".$codigoMedia." > /dev/null &");
}

criarJson:
# Se ocorrer um erro apaga tudo.
if ($erro){
    unlink($ficheiro_ori_caminho);      # Elimina o vídeo
    $bd->query("DELETE FROM med WHERE id='".$codigoMedia."'");
    unlink($ficheiro_thumb_caminho);    # Elimina a thumbnail
    $bd->query("DELETE FROM med_thu WHERE id='".$codigoThumb."'");
}
$json = array("erro"=>$erro, "codigo"=>$codigoMedia, "thumb"=>$codigoThumb, "estado"=>$estado);
echo json_encode($json);
exit;
?>