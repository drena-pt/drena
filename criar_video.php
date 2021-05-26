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
		<script src='/js/jquery.filedrop.js'></script>
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
				
				<div class='p-xl-5 p-4 bg-primary text-light bar'>
					<h2 id='status'>Carregar vídeo</h2>
					<!--<div id='progresso_barra' class='progress percent'>
						<div style='background-color:red;' class='bar'></div >
					</div>
					<div id='status'></div>-->
				</div>

				<div class='p-xl-5 p-4 bg-dark text-light'>

					<div class='row'>
						<div class='col-12 col-sm mb-2'>
							<img id='video_thumb' class='mw-100 shadow rounded-xl' src='/imagens/thumb_carregar.jpg'/>
						</div>
						<div class='col-12 col-sm mb-2'>
						
							<text class='h5' id='tit'>Nenhum vídeo selecionado</text>
							<label id='botao_input_video' for='input_video' class='btn btn-primary' style='cursor:pointer;'>
								<span id='fpe_carregar'>
									Selecionar um vídeo
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-play'/></svg>
								</span>
							</label>

							<form hidden id='form_video' action='pro/carregar_video.php' method='post' enctype='multipart/form-data'>
								<input type='file' id='input_video' name='input_video' accept='video/*'>
								<input type='submit' value='Carregar'>
							</form>

						</div>
					</div>	

				</div>
			</div>
		
			<script>
			$(function() {
				var bar = $('#style_bar_before');
				var status = $('#status');
				var titulo = $('#tit');
				var input = $('#input_video');
				var imagem = $('#video_thumb');

				input.change(function() {
					$('#form_video').submit();
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
							imagem.attr('src','https://media.drena.xyz/thumb/'+json.codigo+'.jpg');
						}

						/*$.ajax({
							type: 'GET',
							url: 'pro/pro_video.php?cod='+json.codigo+'&ext='+json.ext,
							timeout: 8000,
							complete: function(data){
								window.onbeforeunload = null;
								$(window).unbind('beforeunload');
								//window.location.replace('https://drena.xyz/video?id='+json.codigo);
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