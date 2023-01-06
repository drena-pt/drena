<?php
#API - FPE ( Foto de Perfil )
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

#Obtem ação
$ac = $_POST['ac'];

if ($ac=='mudar'){
	#Informações da Foto de Perfil
	$fpe = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_fpe WHERE id='".$_POST['fpe']."';"));
	#Se o utilizador for dono da foto
	if ($fpe['uti']==$uti['id']){
		#Atualiza a foto na tabela do utilizador
		if ($bd->query("UPDATE uti SET fpe='".$fpe['id']."' WHERE id='".$uti['id']."'") === FALSE) {
			echo '{"err": "'.$bd->error.'"}'; exit;
		}
		echo '{"est":"sucesso", "fpe":"'.$url_media.'fpe/'.$fpe['id'].'.jpg"}';
	} else {
		echo "{'err': 'Foto de perfil inválida'}";
	}
	exit;
}

$img_upload = $_FILES['fpe']['tmp_name'];

if (file_exists($img_upload)){

	$img_original = file_get_contents($img_upload);

	#Verificar se o ficheiro é uma imagem
	$tipo = exif_imagetype($img_upload);

	if (!$tipo){
		echo "{'err': 'Apenas são premitidas imagens'}"; exit;
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
	$output = ob_get_contents();

	# Função para gerar um código
	function gerarCodigo($length){   
		$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for($i=0; $i<$length; $i++) 
			$key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
		return $key;
	}

	#Gera código unico
	gerarCodigo:
	$codigo = gerarCodigo(8);
	#Verifica na base de dados se já existe esse código, se sim repete.
	if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_fpe WHERE id='".$codigo."'"))){
		goto gerarCodigo;
	}

	#Cria a foto em .jpg
	if ($jpg_file = fopen($dir_media."fpe/".$codigo.".jpg", "w")){
		fwrite($jpg_file, $output);
		fclose($jpg_file);
	} else {
		echo '{"err": "Não foi possivel guardar a imagem! '.$codigo.'.jpg"}'; exit;
	}
	ob_clean();

	#Regista na base de dados a nova foto
	if ($bd->query("INSERT INTO uti_fpe (id, uti) VALUES('".$codigo."', '".$uti['id']."')") === FALSE) {
		echo '{"err": "'.$bd->error.'"}'; exit;
	}

	#Atualiza a foto na tabela dos utilizadores
	if ($bd->query("UPDATE uti SET fpe='".$codigo."' WHERE id='".$uti['id']."'") === FALSE) {
		echo '{"err": "'.$bd->error.'"}'; exit;
	}

	echo '{"est":"sucesso", "fpe":"'.$url_media.'fpe/'.$codigo.'.jpg"}';
	exit;
}
?>