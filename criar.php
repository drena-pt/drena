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
			<h1 class='p-xl-5 p-4 display-3'>CRIAR</h1>

			<div class='row row-cols-1 row-cols-md-2'>

				<style>
					#cartao_1, #cartao_2, #cartao_3, #cartao_4{
						position: relative;
						overflow: hidden;
					}
					#cartao_1:before, #cartao_2:before, #cartao_3:before, #cartao_4:before{
						content: '';
						width: 200%;
						height: 200%;
						position: absolute;
						top: -50%;
						left: -30%;
						z-index: 4;
						opacity: 0.4;
						background-position: center;
						background-size: 9em;
						background-repeat: no-repeat;
						transform: rotate(10deg);
					}
					#cartao_1:before{background-image: url('node_modules/bootstrap-icons/icons/blockquote-left.svg');}
					#cartao_2:before{background-image: url('node_modules/bootstrap-icons/icons/camera-reels.svg');}
					#cartao_3:before{background-image: url('node_modules/bootstrap-icons/icons/camera.svg');}
					#cartao_4:before{background-image: url('node_modules/bootstrap-icons/icons/volume-up.svg');}
				</style>

				<div class='col'><a class='text-decoration-none' href='pro/projeto.php'><div id='cartao_1' class='bg-light text-dark p-xl-5 p-4 mb-4 rounded-xl shadow'>
					<h2>Projeto</h2>
				</div></a></div>

				<div class='col'><div id='cartao_2' class='bg-primary p-xl-5 p-4 mb-4 rounded-xl shadow'>
					<h2>Vídeo</h2>
				</div></div>

				<div class='col'><div id='cartao_3' class='bg-ciano p-xl-5 p-4 mb-4 rounded-xl shadow'>
					<h2>Imagem</h2>
				</div></div>

				<div class='col'><div id='cartao_4' class='bg-rosa p-xl-5 p-4 mb-4 rounded-xl shadow'>
					<h2>Som</h2>
				</div></div>

			</div>
		</div>

			<!--<div class=' shadow  p-xl-5 p-4 bg-primary text-light'>
				<h2 id='tit'>Novo projeto</h2>
			</div>
			<div class='p-xl-5 p-4 bg-dark text-light'>
				<form action='pro/projeto.php' method='post'>
					<div class='row'>
						<div class='col'>
							<input type='text' class='form-control w-100' id='tit_input' name='tit_input' placeholder='Título do novo projeto'>
						</div>
						<div class='col-2'>
							<button type='submit' class='btn btn-light'>Criar</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<div class='p-xl-5 p-4 bg-primary text-light'>
				<h2 id='tit'>Carregar vídeo</h2>
			</div>
			<div class='p-xl-5 p-4 bg-dark text-light'>
				<form id='carregar_video' action='pro/carregar_video.php' method='post' enctype='multipart/form-data'>
					<input type='file' name='myfile'><br>
					<input type='submit' value='Upload File to Server'>
				</form>
				<div class='progress'>
					<div style='background-color:red;' class='bar'></div >
					<div style='color:blue;' class='percent'>0%</div >
				</div>
				<div id='status'></div>
			</div>
		</div>
		
		<script>
		$('#tit_input').on('input', function() { 
			if($(this).val()){
				$('#tit').text($(this).val())
			} else {
				$('#tit').text('Novo projeto')
			}
		});

		$(function() {
			var bar = $('.bar');
			var percent = $('.percent');
			var status = $('#status');

			$('#carregar_video').ajaxForm({
				beforeSend: function() {
					status.empty();
					var percentVal = '0%';
					bar.width(percentVal);
					percent.html(percentVal);
				},
				uploadProgress: function(event, position, total, percentComplete) {
					var percentVal = percentComplete + '%';
					bar.width(percentVal);
					percent.html(percentVal);
					window.addEventListener('beforeunload', function (e) { 
						e.preventDefault(); 
						e.returnValue = 'wiga'; 
					});
				},
				complete: function(xhr) {
					var json1 = JSON.parse(xhr.responseText);
					status.html('Carregamento completo: <br><b>https://2.drena.xyz/video?id='+json1.codigo+'</b><br>A começar compressão...');
					$.ajax({
						type: 'GET',
						url: 'pro/pro_video.php?cod='+json1.codigo+'&ext='+json1.ext,
						timeout: 8000,
						complete: function(data){
							window.onbeforeunload = null;
							$(window).unbind('beforeunload');
							window.location.replace('https://2.drena.xyz/video?id='+json1.codigo);
						},
						success: function(data){
							if(data != null){console.log(data);}
						}
					});
				}
			});
		}); 
		</script>
		-->
		";
		?>
		</div>
	</body>
</html>