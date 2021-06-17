<?php 
ob_start();
require_once('pro/ligarbd.php');
ob_get_clean();
session_start();
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));
$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));	# Utilizador dono
echo $med_anterior['id'];
if ($med){
	if ($med['tit']){$med_tit = $med['tit'];} else {$med_tit = $med['nom'];}						# Definir título
	if ($_GET['titulo']=='0'){$tem_titulo='//';}													# Se a variavel passada pelo o url "titulo" for 0, comenta o script.
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
		<meta property='og:image' content='https://media.drena.xyz/thumb/".$med["thu"].".jpg' />
		<meta property='og:video' content='https://media.drena.xyz/webm/".$_GET["id"].".webm' />

		<!-- Wavesurfer -->
		<script src='https://unpkg.com/wavesurfer.js'></script>

		<!-- VideoJS -->
		<link href='node_modules/video.js/dist/video-js.css' rel='stylesheet'/>
		<script src='node_modules/video.js/dist/video.min.js'></script>
		<link href='node_modules/@silvermine/videojs-quality-selector/dist/css/quality-selector.css' rel='stylesheet'>
		<script src='node_modules/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js'></script>

		<!-- VideoJS Tema Personalisado -->
		<link href='css/videojs-theme-drena.css' rel='stylesheet' type='text/css'>
		
		<!-- Bootstrap -->
		<script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js' integrity='sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN' crossorigin='anonymous'></script>
		<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' integrity='sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV' crossorigin='anonymous'></script>
		<link rel='stylesheet' type='text/css' href='/css/bootstrap.css'>
		<link rel='stylesheet' type='text/css' href='/css/bootstrap-icons.css'>
		<script>
			$(function (){ $(\"[data-toggle='tooltip']\").tooltip() })
		</script>
	</head>
	<body>";
		if ($med['tip']==1){
			echo "
			<video-js poster='https://media.drena.xyz/thumb/".$med["thu"].".jpg' id='video' class='vjs-theme-fantasy js-focus-invisible vjs-16-9' controls preload='auto'>
				<source src='https://media.drena.xyz/ori/".$_GET["id"].".".end(explode(".", $med['nom']))."' label='Original' selected='true'>
				<source src='https://media.drena.xyz/webm/".$_GET["id"].".webm' label='240P'>
			</video-js>
	
			<script src='node_modules/videojs-titleoverlay/videojs-titleoverlay.js'></script>
			<script>
			videojs('video', {}, function() {
				var player = this;
				//player.controlBar.addChild('QualitySelector');
				".$tem_titulo."player.titleoverlay({title: '".$med_tit."'});
			});
			if ('mediaSession' in navigator) {
				navigator.mediaSession.metadata = new MediaMetadata({
				title: '".$med_tit."',
				artist: '".$med_uti['nut']."',
				artwork: [
					{ src: 'https://media.drena.xyz/thumb/".$med["thu"].".jpg', sizes: '800x450',   type: 'image/png' },
				]
				});
			}
			</script>
			";
		} else {
			echo "<table>
				<tr>
					<td role='button' class='align-middle bg-rosa bg-gradient text-light' onclick='wavesurfer.playPause()' style=\"background-image:url('https://drena.xyz/imagens/carregar_som.jpg');background-size:cover;\">
						<h1 id='botao' class='bi-play m-auto mx-4'></h1>
					</td>
					<td class='w-100 bg-light'><div id='waveform'></div></td>
				</tr>
				";
				if ($_GET['titulo']!='0'){
					echo "<tr><td colspan='2' class='bg-dark'><text class='h5'>".$med_tit."</text></td></tr>";
				}
				echo "
			</table>
			<script>
				var wavesurfer = WaveSurfer.create({
					container: document.querySelector('#waveform'),
					waveColor: '#333',
					progressColor: '#ff4fff',
					cursorColor: '#ff4fff',
					barWidth: 3,
					barRadius: 3,
					cursorWidth: 0,
					height: 100,
					fluid: true,
					barGap: 3
				});
				wavesurfer.on('pause', function () {
					$('#botao').addClass('bi-play');
					$('#botao').removeClass('bi-pause');
				});
				wavesurfer.on('play', function () {
					$('#botao').addClass('bi-pause');
					$('#botao').removeClass('bi-play');
				});
				wavesurfer.load('https://media.drena.xyz/som/".$_GET['id'].".".end(explode(".", $med['nom']))."');
			</script>";
		}
		
		
		echo "
	</body>
";
}