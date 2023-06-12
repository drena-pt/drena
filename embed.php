<?php
# Funções
$funcoes['requerSessao'] = 0;
require_once('pro/fun.php');

$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));
if ($med){

	$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));	# Utilizador dono

	# Formatar Bytes
	function formatSizeUnits($bytes){
		if ($bytes >= 1073741824){
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		}elseif ($bytes >= 1048576){
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		}elseif ($bytes >= 1024){
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		}elseif ($bytes > 1){
			$bytes = $bytes . ' bytes';
		}elseif ($bytes == 1){
			$bytes = $bytes . ' byte';
		}else{
			$bytes = '0 bytes';
		}
		return $bytes;
	}

	$med_tit = $med['tit'];#Definir título
	if ($_GET['titulo']=='0'){$tem_titulo='//';}# Se a variavel passada pelo o url "titulo" for 0, comenta o script.
	echo "
		<head>
			<title>".$med_tit."</title> 
			<meta charset='utf-8'>
			<style>body{margin:0;overflow:hidden;}</style>
	
			<!-- JQuery -->
			<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
	
			<!-- iFrame Resizer contentWindow -->
			<script src='https://cdn.jsdelivr.net/npm/iframe-resizer@4.3.2/js/iframeResizer.contentWindow.min.js'></script>

			<!-- Tags de motor de pequisa -->
			<meta property='og:title' content='".$med_tit."'/>
			<meta property='og:image' content='".$url_media."thumb/".$med["thu"].".jpg' />
			";
			if ($med['tip']==1){
				echo "
				<meta property='og:type' content='video.other'/>

				<!-- VideoJS -->
				<link href='node_modules/video.js/dist/video-js.css' rel='stylesheet'/>
				<script src='node_modules/video.js/dist/video.min.js'></script>
				<link href='node_modules/@silvermine/videojs-quality-selector/dist/css/quality-selector.css' rel='stylesheet'>
				<script src='node_modules/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js'></script>
		
				<!-- VideoJS Tema Personalisado -->
				<link href='css/videojs-theme-drena.css' rel='stylesheet' type='text/css'>
				";
			} else if ($med['tip']==2){
				echo "
				<meta property='og:type' content='music.song'/>
				
				<!-- Wavesurfer -->
				<script src='https://unpkg.com/wavesurfer.js@6.2.0/dist/wavesurfer.min.js'></script>
				
				<!-- Bootstrap -->
				<script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js' integrity='sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN' crossorigin='anonymous'></script>
				<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' integrity='sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV' crossorigin='anonymous'></script>
				<link rel='stylesheet' type='text/css' href='/css/bootstrap.css'>
				<link rel='stylesheet' type='text/css' href='/css/bootstrap-icons.css'>
				<script>
					$(function (){ $(\"[data-bs-toggle='tooltip']\").tooltip() })
				</script>
				";
			} else if ($med['tip']==3){
				echo "
				";
			}
			echo"
		</head>
		<body>";
			if ($med['tip']==1){
				#Obtem o ficheiro original e descobre a extensão
				$med_ori = basename(glob($dir_media."ori/".$med['id']."*")[0]);
				$med_ori_dir = $dir_media."ori/".$med_ori;
				$med_ori_url = $url_media."ori/".$med_ori;

				echo "
				<video-js poster='".$url_media."thumb/".$med["thu"].".jpg' id='video' class='vjs-theme-drena js-focus-invisible vjs-16-9' controls preload='auto'>
					";
					if ($med['est']=='3'){ # Se o estado for 3 (comprimido).
						echo "<source src='".$url_media."comp/".$med["id"].".mp4' label='Comprimido <br>".formatSizeUnits(filesize($dir_media."comp/".$med["id"].".mp4"))."' selected='true'>";
						echo "<source src='".$med_ori_url."' label='Original <br>".formatSizeUnits(filesize($med_ori_dir))."'>";
					} else {
						$tem_seletorQualidade='//';
						if ($med['est']=='5'){ # Se o estado for 5 (convertido).
							echo "<source src='".$url_media."conv/".$med["id"].".mp4' label='Convertido <br>".formatSizeUnits(filesize($dir_media."conv/".$med["id"].".mp4"))."'>";
						} else {
							echo "<source src='".$med_ori_url."' label='Original <br>".formatSizeUnits(filesize($med_ori_dir))."'>";
						}
					}
					echo "
				</video-js>
		
				<script src='/js/videojs-titleoverlay.js'></script>
				<script>
				videojs('video', {}, function() {
					var player = this;
					".$tem_seletorQualidade."player.controlBar.addChild('QualitySelector');
					".$tem_titulo."player.titleoverlay({title: '".$med_tit."', debug: true});
				});
				if ('mediaSession' in navigator) {
					navigator.mediaSession.metadata = new MediaMetadata({
					title: '".$med_tit."',
					artist: '".$med_uti['nut']."',
					artwork: [
						{ src: '".$url_media."thumb/".$med["thu"].".jpg', sizes: '800x450',   type: 'image/png' },
					]
					});
				}
				</script>
				";
			} else if ($med['tip']==2){
				$med_file = basename(glob($dir_media."som/".$med['id']."*")[0]);
				$med_som = $url_media."som/".$med_file;

				if ($med['thu']){
					$audio_botao_play = "<td role='button' class='align-middle text-light' onclick='wavesurfer.playPause()' style=\"background-image:url('".$url_media."thumb/".$med['thu'].".jpg');background-size:cover;\">";
				} else {
					$audio_botao_play = "<td role='button' class='align-middle bg-rosa bg-gradient text-light' onclick='wavesurfer.playPause()'>";
				}
				echo "<table>
					<tr>
						".$audio_botao_play."
							<h1 id='botao' class='bi-play m-auto mx-3 px-5'></h1>
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
						container: '#waveform',
						waveColor: '#333',
						progressColor: '#ff4fff',
						cursorColor: '#ff4fff',
						barWidth: 3,
						barRadius: 3,
						cursorWidth: 0,
						height: 180,
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
					wavesurfer.load('".$med_som."')
					
					wavesurfer.on('ready',(e)=>{
						console.debug('READY')
					})
					
					window.addEventListener('resize', () => {
						wavesurfer.drawer.containerWidth = wavesurfer.drawer.container.clientWidth;
						wavesurfer.drawBuffer();
					});
				</script>";
			} else if ($med['tip']==3){
				$med_file = basename(glob($dir_media."img/".$med['id']."*")[0]);
				$med_img = $url_media."img/".$med_file;

				echo "
				<section style='align-items:center;background-color:#111111;display:flex;flex-wrap:wrap;justify-content:center;height:100%;'>
					<img loading='lazy' style='width:auto;height:auto;max-height:100vh!important;max-width:100vw!important;' src='".$med_img."'></img>
				</section>";
			}
			echo "
		</body>
	";
}