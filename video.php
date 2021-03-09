		<?php 
		require('head.php');
		$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));

		if ($med){
			$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));
			$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$_GET["id"]."' AND uti='".$uti['id']."';"));	#Informações do gosto
			echo "
			<meta property='og:title' content='".$med['nom']."' />
			<meta property='og:type' content='video.other' />
			<meta property='og:image' content='https://media.drena.xyz/thumb/".$_GET["id"].".jpg' />
			<meta property='og:video' content='https://media.drena.xyz/webm/".$_GET["id"].".webm' />
			";
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<?php
			function tempoHumano($time){
			
				$time = time() - $time; // to get the time since that moment
				$time = ($time<1)? 1 : $time;
				$tokens = array (
					31536000 => 'ano',
					2592000 => 'mês',
					604800 => 'semana',
					86400 => 'dia',
					3600 => 'hora',
					60 => 'minuto',
					1 => 'segundo'
				);
			
				foreach ($tokens as $unit => $text) {
					if ($time < $unit) continue;
					$numberOfUnits = floor($time / $unit);
					return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
				}
			
			}
			echo "
			<div class='p-0 my-0 offset-xl-3 col-xl-6'>

				<h3><br></h3>

				<section class='bg-dark'>
					<div class='mw-100'>
						<video poster='https://media.drena.xyz/thumb/".$_GET["id"].".jpg' id='video_1' class='video-js vjs-theme-fantasy js-focus-invisible vjs-16-9' controls preload='auto' data-setup=\"{'language':'pt'}\">
							<source src='https://media.drena.xyz/ori/".$_GET["id"].".".end((explode(".", $med['nom'])))."' label='Original' selected='true'>
							<source src='https://media.drena.xyz/webm/".$_GET["id"].".webm' label='240P'>
						</video>
						<script>
						videojs('video_1', {}, function() {
							var player = this;
							player.fluid(true);
							player.controlBar.addChild('QualitySelector');
						});
						</script>
						<script>
						if ('mediaSession' in navigator) {
							navigator.mediaSession.metadata = new MediaMetadata({
							title: '".$med['nom']."',
							artist: 'Testes',
							artwork: [
								{ src: 'https://media.drena.xyz/thumb/".$_GET["id"].".jpg', sizes: '800x450',   type: 'image/png' },
							]
							});
						}
						</script>
						<!--https://developers.google.com/web/updates/2017/02/media-session#gimme_what_i_want-->
					</div>

					<div class='p-4'>
						<h5 class=''>".$med['nom']."</h5>
						<br>

						<section class='mt-auto'>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<img src='fpe/".base64_encode($med_uti["fot"])."' class='rounded-circle' width='40'>
								</div>
								<div class='col d-flex'>
									<span class='justify-content-center align-self-center'>Publicado por: ".$med_uti['nut']."</span>
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#bar-chart'/></svg>
								</div>
								<div class='col'>
									 visualizações
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg onclick='gosto()' class='bi' style='cursor:pointer;' width='1em' height='1em' fill='currentColor'>
										<use id='botao_gosto' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up-fill' "; if(!$med_gos){echo"hidden";} echo"/>
										<use id='botao_naogosto' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up' "; if($med_gos){echo"hidden";} echo"/>
									</svg>
								</div>
								<div class='col' >
									<span id='texto_gostos'>".$med['gos']."</span> gostos
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#calendar4-week'/></svg>
								</div>
								<div class='col'>
									há ".tempoHumano(strtotime($med['den']))."
								</div>
							</div>
						</section>
					</div>

				</section>

			</div>
			";
			if ($uti){
				echo "
				<script>
				function gosto(){
					$.ajax({
						url: 'pro/med_gos.php?id=".$med['id']."',
						success: function(result) {
							var gostos = +$('#texto_gostos').text();
							if (result==='true'){
								$('#botao_gosto').removeAttr('hidden');
								$('#botao_naogosto').attr('hidden', true);
								$('#texto_gostos').text(gostos + 1);
							} else {
								$('#botao_gosto').attr('hidden', true);
								$('#botao_naogosto').removeAttr('hidden');
								$('#texto_gostos').text(gostos - 1);
							}
						},
						error: function(){
							alert('Ocorreu um erro.');
						}
					});
				}
				</script>
				";
			} else {
				echo "
				<script>
				function gosto(){
					window.open('/entrar','_self');
				}
				</script>
				";
			}
		?>
		</div>
	</body>
</html>