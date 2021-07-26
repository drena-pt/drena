<?php
require 'fun.php'; #Funções

$img_upload = $_FILES['fpe']['tmp_name'];
$nome = $_FILES['fpe']['name'];
$img_original = file_get_contents($img_upload);

#Verificar se o ficheiro é uma imagem
$tipo = exif_imagetype($img_upload);

if (!$tipo){
	echo "Apenas são premitidas imagens.";
	exit;
}

//Criar miniatura (512x512)px 
$img = imagecreatefromstring($img_original);
$width  = imagesx($img);
$height = imagesy($img);
$bdentreX = round($width / 2);
$bdentreY = round($height / 2);
if (imagesx($img)<=imagesy($img)){
	$bdropWidth  = imagesx($img);
	$bdropHeight = imagesx($img);
} else if (imagesy($img)<=imagesx($img)){
	$bdropWidth  = imagesy($img);
	$bdropHeight = imagesy($img);
} else if (imagesy($img)==imagesx($img)){
	$bdropWidth  = imagesx($img);
	$bdropHeight = imagesy($img);
}
$bdropWidthHalf  = round($bdropWidth / 2);
$bdropHeightHalf = round($bdropHeight / 2);
$x1 = max(0, $bdentreX - $bdropWidthHalf);
$y1 = max(0, $bdentreY - $bdropHeightHalf);	
$output = imagecreatetruecolor(1024, 1024);
imagealphablending($output, false);
$transparency = imagecolorallocatealpha($output, 0, 0, 0, 127);
imagefill($output, 0, 0, $transparency);
imagesavealpha($output, true);
imagecopyresized($output,$img,0,0,$x1,$y1,1024,1024,$bdropWidth,$bdropHeight);
ob_start();
imagejpeg($output);
imagedestroy($output);
$output = addslashes(ob_get_contents());

if ($bd->query("INSERT INTO uti_fot (uti, ori, fot, nom) VALUES('".$uti['id']."', '".addslashes($img_original)."', '".$output."', '".$nome."')") === FALSE) {
	echo "Error:".$bd->error;
	exit;
}
ob_clean();

$fot = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_fot WHERE uti='".$uti['id']."' ORDER BY id DESC LIMIT 1"));
if ($bd->query("UPDATE uti SET fot='".$fot['id']."' WHERE id='".$uti['id']."'") === FALSE) {
	echo "Error:".$bd->error;
}
#header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>