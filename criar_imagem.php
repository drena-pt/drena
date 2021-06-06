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
				
				<div class='p-xl-5 p-4 bg-ciano bg-gradient text-light bar'>
					<h2 id='status'>Carregar imagem</h2>
				</div>

				<div class='p-xl-5 p-4 bg-dark text-light text-center '>

					<label for='files' class='btn btn-ciano text-light' style='cursor:pointer;'>
						<span id='fpe_carregar'>
							Selecionar imagens
							<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#upload'/></svg>
						</span>
					</label>
					<br><br>
								
					<form hidden method='post' action='' enctype='multipart/form-data'>
						<input type='file' id='files' name='files[]'' multiple accept='image/*><br>
						<input type='button' id='submit' value='Carregar'>
					</form>

					<div class='row row-cols-2 row-cols-md-3' id='preview'></div>

				</div>

                <style>
                .thumb {
                    filter: brightness(75%);
                }
                .container {
                    position: relative;
                }
                .top-left {
                    position: absolute;
                    top: 50%;
                    left: 100%;
                    transform: translate(-100%, -50%);
                }
                .centered {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
					text-shadow: rgb(0, 0, 0) 0px 0px 10px;
                  }
                </style>

				<script>
				$(document).ready(function(){
					$('#files').change(function() {
					
					   var form_data = new FormData();
					
					   // Read selected files
					   var totalfiles = document.getElementById('files').files.length;
					   for (var index = 0; index < totalfiles; index++) {
						  form_data.append('files[]', document.getElementById('files').files[index]);
					   }
					
					   // AJAX request
					   $.ajax({
						 url: 'ajaxfile.php', 
						 type: 'post',
						 data: form_data,
						 dataType: 'json',
						 contentType: false,
						 processData: false,
						 success: function (response) {
						   for(var index = 0; index < response.length; index++) {
							 var img = response[index]['img'];
							 var link = response[index]['link'];
							 var tit = response[index]['tit'];
							 $('#preview').prepend(\"<div class='col mb-4 container'><a class='text-light' href='\"+link+\"' target='_blank'><img class='thumb shadow rounded-xl w-100' src='\"+img+\"'><div class='centered'><text class='h6'>\"+tit+\"</text></div></a></div>\");
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
		</div>
	</body>
</html>