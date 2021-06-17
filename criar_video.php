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
				
				<div class='p-xl-5 p-4 bg-primary bg-gradient text-light bar'>
					<h2 id='status'>"._('Carregar vídeo')."</h2>
				</div>

				<div class='p-xl-5 p-4 bg-dark text-light'>

					<div class='row'>
						<div class='col-12 col-sm mb-2'>
							<img id='video_thumb' class='mw-100 shadow rounded-xl' src='/imagens/carregar_video.jpg'/>
						</div>
						<div class='col-12 col-sm mb-2'>
						
							<text class='h5' id='tit'>"._('Nenhum vídeo selecionado')."</text><br><br>
							<label id='botao_input_video' for='input_video' class='btn btn-primary' style='cursor:pointer;'>
								<span id='fpe_carregar'>
									"._('Selecionar um vídeo')." 
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#upload'/></svg>
								</span>
							</label>

							<form hidden id='form_video' action='pro/carregar_video.php' method='post' enctype='multipart/form-data'>
								<input type='file' id='input_video' name='input_video' accept='video/*'>
								<input type='submit'>
							</form>

							<section class='mt-auto' id='video_info' hidden>
								<div class='row mb-1'>
									<div class='col-auto pr-0 text-center'>
										<svg class='bi' width='1em' height='1em' fill='currentColor'>
											<use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-play'/>
										</svg>
									</div>
									<div class='col' >
										<span>"._('Informações do vídeo')."</span><br>
										<span id='video_info_tamanho'></span><br>
										<span id='video_info_duracao'></span>
									</div>
								</div>
								<br>
							</section>

							<a hidden id='botao_ver_video' href='/videos' class='btn btn-primary'>
								"._('Ver vídeo')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#play'/></svg>
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
				var myVideos = [];

				var bar = $('#style_bar_before');
				var status = $('#status');
				var titulo = $('#tit');
				var input = $('#input_video');
				var imagem = $('#video_thumb');

				input.change(function() {
					$('#form_video').submit();
					$('#video_info_tamanho').html(formatBytes(this.files[0].size));

						var files = this.files;
						myVideos.push(files[0]);
						var video = document.createElement('video');
						video.preload = 'metadata';
					  
						video.onloadedmetadata = function() {
						  window.URL.revokeObjectURL(video.src);
						  var duration = video.duration;
						  myVideos[myVideos.length - 1].duration = duration;
						  $('#video_info_duracao').html(secondsToHms(video.duration));
						}
					  
						video.src = URL.createObjectURL(files[0]);;

					$('#video_info').removeAttr('hidden');
				});

				$('#form_video').ajaxForm({
					beforeSend: function() {
						//Executa quando começar o upload:

						var percentVal = '0%';
						titulo.html(input.val().split('\\\\').pop());
						$('#botao_input_video').hide();
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
							imagem.attr('src','https://media.drena.xyz/thumb/'+json.thumb+'.jpg');
							$('#botao_ver_video').removeAttr('hidden');
							$('#botao_ver_video').attr('href', '/media?id='+json.codigo);
						}

						/*$.ajax({
							type: 'GET',
							url: 'pro/pro_video.php?cod='+json.codigo+'&ext='+json.ext,
							timeout: 8000,
							complete: function(data){
								window.onbeforeunload = null;
								$(window).unbind('beforeunload');
							},
							success: function(data){
								if(data != null){console.log(data);}
							}
						});*/

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