<?php
# Funções
require 'fun.php';

if ($uti['adm']!='1'){
    echo "boi baza";
    exit;
}

$caminho = "/home/guilha/www/media.drena.xyz/";

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

# Função para gerar thumbnail
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality, $extensao){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $dst_img = imagecreatetruecolor($max_width, $max_height);
 
    switch (strtolower($extensao)) {
       case 'jpeg':
       case 'jpg':
          $src_img = imagecreatefromjpeg($source_file);
       break;
       case 'png':
          $src_img = imagecreatefrompng($source_file);
       break;
       case 'gif':
          $src_img = imagecreatefromgif($source_file);
       break;
    }
 
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

$pesquisa = "SELECT * FROM med WHERE tip='3' AND thu IS NULL ORDER by den ASC";
if ($resultado = $bd->query($pesquisa)) {
    while ($campo = $resultado->fetch_assoc()) {

        echo $campo['nom']."<br>";

        gerarCodigoThumb:
        $codigoThumb = gerarCodigo(16);
        # Verifica na base de dados se já existe esse código, se sim repete.
        if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM thu WHERE id='".$codigoThumb."'"))){
           goto gerarCodigoThumb;
        }
        
        $extee = end(explode(".", $campo['nom']));

        $caminhoImagem = $caminho."img/".$campo['id'].".".$extee;
        $caminhoThumb = $caminho."thumb/".$codigoThumb.".jpg";


        # Processa a thumbnail para o tamanho ideal
        resize_crop_image(640, 480, $caminhoImagem, $caminhoThumb, 30, $extee);

        $bd->query("UPDATE med SET thu='".$codigoThumb."' WHERE id='".$campo["id"]."';");

    }

}

?>