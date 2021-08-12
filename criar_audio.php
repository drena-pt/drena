		<?php 
		require('head.php');
		if (!$uti){
			header("Location: /entrar.php");
			exit;
		}
		?>
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
				
				<div class='p-xl-5 p-4 bg-rosa bg-gradient text-light bar'>
					<h2 id='status'>"._('Carregar áudio')."</h2>
				</div>

				<div class='p-xl-5 p-4 bg-dark text-light'>

					<div class='row'>
						<div class='col-12 col-sm mb-2'>
							<img id='audio_thumb' class='mw-100 shadow rounded-xl' src='/imagens/carregar_som.jpg'/>
						</div>
						<div class='col-12 col-sm-8 mb-2'>
						
							<text class='h5' id='tit'>"._('Nenhum áudio selecionado')."</text><br><br>
							<label id='botao_input_audio' for='input_audio' class='btn btn-rosa text-light' style='cursor:pointer;'>
								<span id='fpe_carregar'>
									"._('Selecionar um áudio')." <i class='bi bi-upload'></i>
								</span>
							</label>

							<form hidden id='form_audio' action='pro/carregar_audio.php' method='post' enctype='multipart/form-data'>
								<input type='file' id='input_audio' name='input_audio' accept='audio/*'>
								<input type='submit'>
							</form>

							<section class='mt-auto' id='audio_info' hidden>
								<div class='row mb-1'>
									<div class='col-auto pr-0 text-center'>
										<i class='bi bi-file-earmark-music'></i>
									</div>
									<div class='col'>
										<span>"._('Informações do áudio')."</span><br>
										<span id='audio_info_tamanho'></span><br>
										<span id='audio_info_duracao'></span>
									</div>
								</div>
								<br>
							</section>

							<a hidden id='botao_ver_audio' class='btn btn-rosa text-light'>
								"._('Ouvir áudio')." <i class='bi bi-play'></i>
							</a>

						</div>
					</div>	

				</div>
			</div>
		
			<script>
			$(function() {
				function formatBytes(a,b=2){if(0===a)return'0 Bytes';const c=0>b?0:b,d=Math.floor(Math.log(a)/Math.log(1024));return parseFloat((a/Math.pow(1024,d)).toFixed(c))+' '+['Bytes','KB','MB','GB','TB','PB','EB','ZB','YB'][d]}

				function secondsToHms(d) {
					d = Number(d);
					var h = Math.floor(d / 3600);
					var m = Math.floor(d % 3600 / 60);
					var s = Math.floor(d % 3600 % 60);
				
					var hDisplay = h > 0 ? h + (h == 1 ? ' "._('hora').", ' : ' "._('horas').", ') : '';
					var mDisplay = m > 0 ? m + (m == 1 ? ' "._('minuto').", ' : ' "._('minutos').", ') : '';
					var sDisplay = s > 0 ? s + (s == 1 ? ' "._('segundo')."' : ' "._('segundos')."') : '';
					return hDisplay + mDisplay + sDisplay; 
				}
				var myaudios = [];

				var bar = $('#style_bar_before');
				var status = $('#status');
				var titulo = $('#tit');
				var input = $('#input_audio');
				var imagem = $('#audio_thumb');

				input.change(function() {
					$('#form_audio').submit();
					$('#audio_info_tamanho').html(formatBytes(this.files[0].size));

						var files = this.files;
						myaudios.push(files[0]);
						var audio = document.createElement('audio');
						audio.preload = 'metadata';
					  
						audio.onloadedmetadata = function() {
						  window.URL.revokeObjectURL(audio.src);
						  var duration = audio.duration;
						  myaudios[myaudios.length - 1].duration = duration;
						  $('#audio_info_duracao').html(secondsToHms(audio.duration));
						}
					  
						audio.src = URL.createObjectURL(files[0]);;

					$('#audio_info').removeAttr('hidden');
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
						status.html(percentVal + ' "._('carregado')."');
					},
					complete: function(xhr) {
						//Executa quando terminar o upload:

						var json = JSON.parse(xhr.responseText);
						console.log(json);
						if (json.erro){
							status.html(json.erro);
						} else {
							status.html('"._('Carregamento completo')."');
							$('#botao_ver_audio').removeAttr('hidden');
							$('#botao_ver_audio').attr('href', '/media?id='+json.codigo);
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