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
		<?php
		$append_med = "
		<div class='col p-1 p-sm-2'><div class='ratio ratio-4x3'>
			<a class='text-light text-decoration-none' href='\"+link+\"'>
				<div class='bg-rosa contentor_med h-100 rounded-xl d-flex' style='background-image:url(\"+thumb+\");'>
					<div class='rounded-bottom d-flex w-100 align-items-center align-self-end bg-dark bg-opacity-75 p-2'>
						<span class='mx-1 text-ciano'><i class='bi bi-image'></i></span>
						<span class='overflow-hidden'>\"+tit+\"</span>
					</div>
				</div>
			</a>
		</div></div>
		";
		
		echo "
		<div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<div class='shadow p-0 my-0 mb-xl-4'>
				
				<div class='p-xl-5 p-4 bg-ciano bg-gradient text-light bar'>
					<h2 id='status'>"._('Carregar imagens')."</h2>
				</div>

				<div class='p-xl-5 p-4 bg-dark text-light text-center '>

					<label for='files' class='btn btn-ciano text-light' style='cursor:pointer;'>
						<span id='fpe_carregar'>
							"._('Selecionar imagens')." <i class='bi bi-upload'></i>
						</span>
					</label>
					<br><br>
								
					<form hidden method='post' action='' enctype='multipart/form-data'>
						<input type='file' id='files' name='files[]' multiple accept='image/*'><br>
						<input type='button' id='submit'>
					</form>

					<div class='row row-cols-2 row-cols-md-3' id='preview'></div>

				</div>

				<script>
				$(document).ready(function(){
					var status = $('#status');
					
					$('#files').change(function() {
					
						var form_data = new FormData();
						
						// Read selected files
						var totalfiles = document.getElementById('files').files.length;
						for (var index = 0; index < totalfiles; index++) {
						  form_data.append('files[]', document.getElementById('files').files[index]);
						}
					
						// AJAX request
						$.ajax({
							beforeSend: function() {
								status.html('"._('A carregar...')."');
							},
							url: 'pro/carregar_imagens.php', 
							type: 'post',
							data: form_data,
							dataType: 'json',
							contentType: false,
							processData: false,
							success: function (response) {
								status.html('"._('Carregamento completo')."');
								for(var index = 0; index < response.length; index++) {
									var img = response[index]['img'];
									var link = response[index]['link'];
									var tit = response[index]['tit'];
									var erro = response[index]['erro'];
									var thumb = response[index]['thumb'];
									if (erro){
										$('#preview').prepend(\"<div class='col mb-4 contentor'><div class='rounded-xl'><img class='shadow rounded-xl w-100' src='imagens/thumb_imagem.jpg'><div class='texto-contentor h6'>\"+erro+\"</div></div></div>\");
									} else {
										$('#preview').prepend(\"".trim(preg_replace('/\s\s+/', ' ', $append_med))."\");
									}
								}
							}
						});
					
					});
				});
				</script>

			</div>
		</div>
		";
		?>
	</body>
</html>