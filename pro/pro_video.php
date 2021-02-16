<?php
require '../vendor/autoload.php';

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

$caminho = "/home/root/media.drena.xyz/";
$ficheiro_ori_caminho = $caminho."ori/".$_GET['cod'].".".$_GET['ext'];
$ficheiro_thumb_caminho = $caminho."thumb/".$_GET['cod'].".jpg";
$ficheiro_caminho = $caminho."webm/". $_GET['cod'].".webm";

$video_dimensions = $ffprobe
    ->streams($ficheiro_ori_caminho)        // extracts streams informations
    ->videos()                              // filters video streams
    ->first()                               // returns the first video stream
    ->getDimensions();                      // returns a FFMpeg\Coordinate\Dimension object

$duracao = $ffprobe
    ->streams($ficheiro_ori_caminho)
    ->first()
    ->get('duration');

$original_width = $video_dimensions->getWidth();
$original_height = $video_dimensions->getHeight();
$new_height = 720;
$numero_magico = $original_height/$new_height;
$new_width = round($original_width/$numero_magico);

$video = $ffmpeg->open($ficheiro_ori_caminho);
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($duracao/2))
    ->save($ficheiro_thumb_caminho);

function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = imagecreatefromjpeg($source_file);
    
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
    
    imagejpeg($dst_img, $dst_dir, $quality);
 
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}

resize_crop_image(800, 450, $ficheiro_thumb_caminho, $ficheiro_thumb_caminho, 30);

$video->filters()->resize(new FFMpeg\Coordinate\Dimension($new_width, $new_height));
$format = new FFMpeg\Format\Video\WebM();
#$format
#    ->setKiloBitrate(1280);
$video
    ->save($format, $ficheiro_caminho);

criarJson:
$json = array("duracao"=>$duracao, "lo"=>$original_width, "ao"=>$original_height, "ln"=>$new_width, "an"=>$new_height);
echo json_encode($json);
exit;
?>