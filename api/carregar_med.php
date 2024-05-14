<?php
#API - Carregar Média (Imagens \ uma de cada vez)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

#PHP FFMpeg
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

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

#Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

#Função para gerar thumbnail
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality){
   $imgsize = getimagesize($source_file);
   $width = $imgsize[0];
   $height = $imgsize[1];
   $dst_img = imagecreatetruecolor($max_width, $max_height);

   #Cria imagem a partir da extensão (jpg,png ou gif)
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

$ficheiro = $_FILES["file"];

#O ficheiro foi carregado?
if(isset($ficheiro['name']) && $ficheiro['name'] != ''){

   if (is_uploaded_file($ficheiro['tmp_name'])) {

      #Informações do ficheiro (Tipo e Extensão)
      $ficheiro_type = $ficheiro['type'];
      $ficheiro_ext = end(explode(".", $ficheiro['name']));

      #O tipo tem a string 'video'
      if (str_contains($ficheiro_type, "video")) {
         $med_tip = 1; #Tipo
         $caminho = "ori/"; #Caminho

      #O tipo tem a string 'audio'
      } else if (str_contains($ficheiro_type, "audio")) {
         $med_tip = 2;
         $caminho = "som/";

      #O tipo tem a string 'image'
      } else if (str_contains($ficheiro_type, "image")) {
         $med_tip = 3;
         $caminho = "img/";

      #Formato inválido
      } else {
         $erro = "O formato '.".$ficheiro_ext."' não é suportado";
         goto erro;
      }

      #ACEITO

      gerarCodigo:
      $med_id = gerarCodigo(16);
      #SQL: Já existe este código? Se sim, repete.
      if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$med_id."'"))){
         goto gerarCodigo;
      }

      #Caminho final do ficheiro
      $ficheiro_cam = $dir_media.$caminho.$med_id.".".$ficheiro_ext;

      #Carrega o ficheiro para o caminho final
      if(move_uploaded_file($ficheiro['tmp_name'],$ficheiro_cam)){
         #Título da média
         $tit = substr($ficheiro['name'],0,strrpos($ficheiro['name'],'.'));

         #Regista a média na base de dados
         $bd->query("INSERT INTO med (id, uti, tit, tip) VALUES('".$med_id."', '".$uti['id']."', '".$tit."', '".$med_tip."');");
      } else {
         $erro = "Não foi possivel guardar o ficheiro";
         goto erro;
      }

      #Processos extra exclusivo para vídeo e foto
      if ($med_tip==1 OR $med_tip==3){

         #Gera thumbnail se for um vídeo ou uma imagem
         gerarCodigoThumb:
         $thu_id = gerarCodigo(16);
         #SQL: Já existe esse código? Se sim, repete.
         if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_thu WHERE id='".$thu_id."'"))){
            goto gerarCodigoThumb;
         }

         #Caminho da thumbnail
         $thu_cam = $dir_media."thumb/".$thu_id.".jpg";

         #Processos especiais para vídeo
         if ($med_tip==1){

            $max_bitrate = 7996544;
            #Obtem o bitrate do vídeo
            $bitrate = $ffprobe
               ->streams($ficheiro_cam)
               ->videos()
               ->first() 
               ->get('bit_rate');

            if ($bitrate>=$max_bitrate){
               $estado = 1;
            } else {
               $estado = 0;
            }

            #Obtem o codec do vídeo
            $codec = $ffprobe
               ->streams($ficheiro_cam)
               ->videos()
               ->first() 
               ->get('codec_name');

            if ($codec=='hevc'){
               $estado = 4;    # Codec não suportado para web
            }

            #Obtem duração do vídeo
            $video_duracao = $ffprobe
               ->streams($ficheiro_cam)
               ->first()
               ->get('duration');

            #Gera thumbnail
            $video = $ffmpeg->open($ficheiro_cam);
            $video
               ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($video_duracao/2))
               ->save($thu_cam);

            #Processa a thumbnail para o tamanho ideal
            resize_crop_image(800, 450, $thu_cam, $thu_cam, 30);

            #Atualiza o estado do vídeo
            $bd->query("UPDATE med SET est='".$estado."' WHERE id='".$med_id."';");

            if ($estado==4){ # Converte o vídeo para X264 se o codec não for suportado para web
               exec("php ".$dir_site."pro/med_compressao.php ".$med_id." > /dev/null &");
            }

         #Processos especiais para foto
         } else if ($med_tip==3){
            resize_crop_image(640, 480, $ficheiro_cam, $thu_cam, 30);
         }

         #Regista a thumbnail na base de dados
         $bd->query("INSERT INTO med_thu (id, med) VALUES('".$thu_id."', '".$med_id."');");
         $bd->query("UPDATE med SET thu='".$thu_id."' WHERE id='".$med_id."';");
      }
      
      

      #SUCESSO!
      echo json_encode(array("id"=>$med_id, "link"=>$url_site."m/".$med_id, "thumb"=>$url_media."thumb/".$thu_id.".jpg", "tit"=>$tit, "tip"=>$med_tip, "est"=>$estado));
      exit;
  
   } else {
      $erro = "Não foi possivel carregar o ficheiro";
   }

} else {
   $erro = "Ficheiro não detetado";
}

#ERRO
erro:
header('HTTP/1.1 400 Bad Request');
echo json_encode(array("tit"=>$ficheiro['name'],"erro"=>$erro));
die;
?>