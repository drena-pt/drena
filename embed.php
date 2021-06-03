<?php 
ob_start();
require_once('pro/ligarbd.php');
ob_get_clean();
session_start();
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."' AND tip='1'"));
$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));	#Utilizador dono do vídeo
if ($med){
	if ($med['tit']){$med_tit = $med['tit'];} else {$med_tit = $med['nom'];}						#Definir título do vídeo
	if ($_GET['titulo']=='0'){$tem_titulo='//';}													#Se a variavel passada pelo o url "titulo" for 0, comenta o script.
	echo "
	<head>
		<title>".$med_tit."</title> 
		<meta charset='utf-8'>
		<style>body{margin:0;overflow:hidden;}@font-face{font-family:'MADE TOMMY Regular';src: url('fontes/MADE TOMMY Regular.otf');}</style>

		<!-- JQuery -->
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
		
		<!-- Tags de motor de pequisa -->
		<meta property='og:title' content='".$med_tit."'/>
		<meta property='og:type' content='video.other' />
		<meta property='og:image' content='https://media.drena.xyz/thumb/".$_GET["id"].".jpg' />
		<meta property='og:video' content='https://media.drena.xyz/webm/".$_GET["id"].".webm' />

		<!-- VideoJS -->
		<link href='node_modules/video.js/dist/video-js.css' rel='stylesheet'/>
		<script src='node_modules/video.js/dist/video.min.js'></script>
		<link href='node_modules/@silvermine/videojs-quality-selector/dist/css/quality-selector.css' rel='stylesheet'>
		<script src='node_modules/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js'></script>

		<!-- VideoJS Tema Personalisado -->
		<link href='css/videojs-theme-drena.css' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<video-js poster='https://media.drena.xyz/thumb/".$_GET["id"].".jpg' id='video' class='vjs-theme-fantasy js-focus-invisible vjs-16-9' controls preload='auto'>
			<source src='https://media.drena.xyz/ori/".$_GET["id"].".".end((explode(".", $med['nom'])))."' label='Original' selected='true'>
			<source src='https://media.drena.xyz/webm/".$_GET["id"].".webm' label='240P'>
		</video-js>

		<script src='node_modules/videojs-titleoverlay/videojs-titleoverlay.js'></script>
		<script>
		videojs('video', {}, function() {
			var player = this;
			player.controlBar.addChild('QualitySelector');
			".$tem_titulo."player.titleoverlay({title: '".$med_tit."'});
		});
		if ('mediaSession' in navigator) {
			navigator.mediaSession.metadata = new MediaMetadata({
			title: '".$med_tit."',
			artist: '".$med_uti['nut']."',
			artwork: [
				{ src: 'https://media.drena.xyz/thumb/".$_GET["id"].".jpg', sizes: '800x450',   type: 'image/png' },
			]
			});
		}
		</script>
	</body>
";
}