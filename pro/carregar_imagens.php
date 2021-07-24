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

   # Cria imagem a partir da extensão (jpg,png ou gif)
   switch (strtolower(end(explode(".", $source_file)))) {
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

// Count total files
$countfiles = count($_FILES['files']['name']);
// To store uploaded files path
$files_arr = array();

// Loop all files
for($index = 0;$index < $countfiles;$index++){

   if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){

      if (is_uploaded_file($_FILES['files']['tmp_name'][$index])) {
         $ficheiro_mime = mime_content_type($_FILES['files']['tmp_name'][$index]);
         $ficheiro_ext = end(explode(".", $_FILES['files']['name'][$index]));
     
         if (strpos($ficheiro_mime, "image") === false) { # Se o mime não tiver a string 'image/'
             $erro = "O ficheiro não é uma imagem: ".$ficheiro_mime;
             $files_arr[] = array("tit"=>$_FILES['files']['name'][$index],"erro"=>$erro);
         } else if ($ficheiro_ext=="ORF"){ # Se a imagem for tiver uma extensão não suportada
            $erro = "O formato não '".$ficheiro_ext."' é suportado.";
            $files_arr[] = array("tit"=>$_FILES['files']['name'][$index],"erro"=>$erro);
         } else {

            gerarCodigo:
            $codigo = gerarCodigo(16);
            # Verifica na base de dados se já existe esse código, se sim repete.
            if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$codigo."'"))){
               goto gerarCodigo;
            }

            // File path
            $path = $dir_media."img/".$codigo.".".$ficheiro_ext;

            gerarCodigoThumb:
            $codigoThumb = gerarCodigo(16);
            # Verifica na base de dados se já existe esse código, se sim repete.
            if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_thu WHERE id='".$codigoThumb."'"))){
               goto gerarCodigoThumb;
            }

            // File path
            $caminhoThumb = $dir_media."thumb/".$codigoThumb.".jpg";

            // Upload file
            if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path)){
               $nom = $_FILES['files']['name'][$index];# Nome da imagem
               $tit = substr($nom,0,strrpos($nom,'.'));# Título da imagem

               # Processa a thumbnail para o tamanho ideal
               resize_crop_image(640, 480, $path, $caminhoThumb, 30);

               # Regista a thumbnail na base de dados
               $bd->query("INSERT INTO med_thu (id, med) VALUES('".$codigoThumb."', '".$codigo."');");

               #Regista a imagem na base de dados
               $bd->query("INSERT INTO med (id, uti, nom, tit, tip, thu) VALUES('".$codigo."', '".$uti['id']."', '".$nom."', '".$tit."', '3', '".$codigoThumb."');");

               # Adiciona ao Json
               $files_arr[] = array("link"=>$url_site."media?id=".$codigo,"thumb"=>$url_media."thumb/".$codigoThumb.".jpg","tit"=>$tit);
               
            } else {
               $erro = "Não foi possivel carregar o ficheiro.";
               $files_arr[] = array("tit"=>$_FILES['files']['name'][$index],"erro"=>$erro);
            }
         }
     
      } else {
         $erro = "Não foi possivel detetar o tipo de ficheiro.";
         $files_arr[] = array("tit"=>$_FILES['files']['name'][$index],"erro"=>$erro);
      }
   }
}

echo json_encode($files_arr);
die;
?>