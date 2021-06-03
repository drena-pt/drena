		<?php 
		require('head.php');
		if (!$uti){
			header("Location: /entrar.php");
			exit;
		}
		?>
		<style>
		.bar{
			background-color: red;
		}
		.percent{
			color: blue;
		}
		</style>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<?php
		echo "
		<div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>

			<div class='shadow p-0 my-0 mb-xl-4'>
				<style>
				.bar{
					width: 100%;
					position: relative;
				}
				.bar:before{
					background-color: #fff;
					opacity: 0.25;
					content: ' ';
					height: 100%;
					position: absolute;
					left: 0;
					top: 0;
					z-index: 1;
				}
				</style>
				<style id='style_bar_before'></style>
				
				<div class='p-xl-5 p-4 bg-rosa text-light bar'>
					<h2 id='status'>Carregar áudio</h2>
				</div>

				<div class='p-xl-5 p-4 bg-dark text-light'>

					<div class='row'>
						<div class='col-12 col-sm mb-2'>
							<img id='audio_thumb' class='mw-100 shadow rounded-xl' src='/imagens/carregar_som.jpg'/>
						</div>
						<div class='col-12 col-sm-8 mb-2'>
						
							<text class='h5' id='tit'>Nenhum áudio selecionado<br></text><br>
							<label id='botao_input_audio' for='input_audio' class='btn btn-rosa text-light' style='cursor:pointer;'>
								<span id='fpe_carregar'>
									Selecionar um áudio
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#upload'/></svg>
								</span>
							</label>

							<form hidden id='form_audio' action='pro/carregar_audio.php' method='post' enctype='multipart/form-data'>
								<input type='file' id='input_audio' name='input_audio' accept='audio/*'>
								<input type='submit' value='Carregar'>
							</form>

							<section class='mt-auto' id='video_info' hidden>
								<div class='row mb-1'>
									<div class='col-auto pr-0 text-center'>
										<svg class='bi' width='1em' height='1em' fill='currentColor'>
											<use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-play'/>
										</svg>
									</div>
									<div class='col' >
										<span>Informações do vídeo:</span><br>
										<span id='video_info_tamanho'></span><br>
										<span id='video_info_duracao'></span>
									</div>
								</div>
								<br>
							</section>

							<a hidden id='botao_ver_audio' class='btn btn-rosa text-light'>
								Ouvir áudio <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#play'/></svg>
							</a>

						</div>
					</div>	

				</div>
			</div>
		
			<script>
			$(function() {
				var bar = $('#style_bar_before');
				var status = $('#status');
				var titulo = $('#tit');
				var input = $('#input_audio');
				var imagem = $('#audio_thumb');

				input.change(function() {
					$('#form_audio').submit();
				});

				$('#form_audio').ajaxForm({
					beforeSend: function() {
						//Executa quando começar o upload:

						var percentVal = '0%';
						titulo.html(input.val().split('\\\\').pop());
						$('#botao_input_audio').hide();
					},
					uploadProgress: function(event, position, total, percentComplete) {
						//Executa durante o upload:

						var percentVal = percentComplete + '%';
						bar.html('.bar:before{width:'+percentVal+';}');
						status.html(percentVal + ' carregado');
					},
					complete: function(xhr) {
						//Executa quando terminar o upload:

						var json = JSON.parse(xhr.responseText);
						console.log(json);
						if (json.erro){
							status.html(json.erro);
						} else {
							status.html('Carregamento completo');
							$('#botao_ver_audio').removeAttr('hidden');
							$('#botao_ver_audio').attr('href', '/audio?id='+json.codigo);
						}
					}
				});
			}); 
			</script>
		</div>
		";
		?>
		</div>
	</body>
</html>