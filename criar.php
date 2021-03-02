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
		<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<div class='p-xl-5 p-4 bg-primary text-light'>
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
					status.html('Upload feito: '+json1.codigo+'<br>https://2.drena.xyz/video?id='+json1.codigo+'<br>A começar compressão...');
					$.ajax({
						type: 'GET',
						url: 'pro/pro_video.php?cod='+json1.codigo+'&ext='+json1.ext,
						success: function(data){
							//if(data != null) $('#content').text(data)
							console.log('ain');
						}
					});
				}
			});
		}); 
		</script>
		";
		?>
		</div>
	</body>
</html>