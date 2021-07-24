<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
# Conectar à base de dados
require 'fun.php';

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

$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET['med']."';"));
$thumb_post = $_FILES['thu'];

if (($med['tip']=='1' OR $med['tip']=='2') AND $med['uti']==$uti['id']){ # Se a media for um vídeo ou audio e o utilizador for dono

   if (is_uploaded_file($thumb_post['tmp_name'])){ # Se o ficheiro foi carregado
      $thumb_mime = mime_content_type($thumb_post['tmp_name']);
      $thumb_ext = end(explode(".", $thumb_post['name']));
      
      if (strpos($thumb_mime, "image") === false) { # Se o mime não tiver a string 'image/'
         $erro = "O ficheiro não é uma imagem: ".$thumb_mime;
         exit;
      } else {

         gerarCodigoThumb:
         $codigoThumb = gerarCodigo(16);
         # Verifica na base de dados se já existe esse código, se sim repete.
         if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_thu WHERE id='".$codigoThumb."'"))){
            goto gerarCodigoThumb;
         }
   
         $caminhoThumb = $dir_media."thumb/".$codigoThumb.".jpg";
   
         if ($med["tip"]=='1'){
            # Processa a thumbnail para o tamanho de vídeo (16:9)
            resize_crop_image(800, 450, $thumb_post['tmp_name'], $caminhoThumb, 30);
         } else {
            # Processa a thumbnail para o tamanho de áudio (1:1)
            resize_crop_image(512, 512, $thumb_post['tmp_name'], $caminhoThumb, 30);
         }
   
         # Regista a thumbnail na base de dados
         $bd->query("INSERT INTO med_thu (id, med) VALUES('".$codigoThumb."', '".$med["id"]."');");
   
         # Atualiza o id da thumb na media
         if($bd->query("UPDATE med SET thu='".$codigoThumb."' WHERE id='".$med["id"]."';") === FALSE){
            echo "Error:".$bd->error;
         }
   
         exit;
      }

   } else { # Ficheirn não foi carregado
      echo "Erro: Não foi possivel carregar o ficheiro.";
      exit;
   }

} else {
   echo "Erro: Não foi possivel alterar a miniatura.";
   exit;
}

die;
?>