		<?php 
		require('head.php');
		if (!$uti){
			header("Location: /entrar.php");
			exit;
		}
		?>
		<style>
		#criar_header {
			background-image: linear-gradient(-70deg,#00cee9,#6c4fff,#ff4fff);
		}
		.dz-preview .dz-image, .dz-preview.dz-image-preview{
			border-radius: 10px !important;
		}
		.dropzone {
			cursor: pointer;
				}
				.dropzone .dz-message .dz-button {
			background: none;
			color: inherit;
			border: none;
			padding: 0;
			font: inherit;
			outline: inherit;
		}
		.dropzone .dz-message {
			width: 100%;
			text-align: center;
			margin: 2em 0;
		}
		</style>
		
		<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

		<!-- Custom Dropzone preview template -->
		<script id="drena-preview-template" type="text/html">
			<a class="text-light text-decoration-none col p-1 p-sm-2">
				<div class="ratio ratio-4x3">
					<div class="dz-preview dz-file-preview">
						<div class="dz-thumb bg-primary contentor_med h-100 rounded-xl d-flex">
						<section class="rounded-bottom d-flex w-100 align-self-end bg-dark bg-opacity-75">
							<div class="w-100">
								<div class="dz-upload position-static bg-light bg-opacity-75" style="height:4px;width: 0%;" data-dz-uploadprogress></div>
								<div class="m-2 overflow-hidden">
									<i class="bi mx-1 dz-icon"></i><span class="overflow-hidden" data-dz-name></span>
									<br><i class="bi bi-hdd mx-1 text-light"></i><span data-dz-size></span>
								</div>
							<div>
						</section>
						</div>
					</div>
				</div>
			</a>
		</script>

	</head>
	<body>
		<?php require('header.php');
		
		echo "
		<div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<div class='shadow p-0 my-0 mb-xl-4'>
				
				<div id='criar_header' class='p-xl-5 p-4 bg-primary text-light'>
					<h2>"._('Carregar médias')."</h2>
					<span>
						<span class='badge bg-light text-dark'><i class='bi bi-image'></i>"._("Imagens")."</span>
						<span class='badge bg-light text-dark'><i class='bi bi-camera-video'></i>"._("Vídeos")."</span>
						<span class='badge bg-light text-dark'><i class='bi bi-soundwave'></i>"._("Áudios")."</span>
					</span>
				</div>

				<div class='text-light'>

					<form class='dropzone bg-dark border-0 mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3' id='dropzone_form'>
					</form>

					<script>
					$('#dropzone_form').dropzone({
						previewTemplate: $('#drena-preview-template').html(),
						init: function() {
							this.on('addedfile', function(file) {
							  $('.dropzone .dz-message').after(file.previewElement);
							});
						  },
						thumbnail: function(file, dataUrl) {
							file.previewElement.querySelector('.dz-thumb').style.backgroundImage='url('+dataUrl+')';
						},
						url: '/api/carregar_med.php',
						maxFilesize: 3072,
						acceptedFiles: 'image/*,video/*,audio/*',
						headers: {
							'Authorization': Cookies.get('drena_token')
						},
						success: function(file, response) {
							//Link para a média
							file.previewElement.href = response.link;

							file.previewElement.querySelector('.dz-thumb').style.backgroundImage='url('+response.thumb+')';

							var med_icon = file.previewElement.querySelector('.dz-icon');
							if (response.tip=='1') {
								med_icon.classList.add('bi-camera-video', 'text-primary');
							} else if (response.tip=='2') {
								med_icon.classList.add('bi-soundwave', 'text-rosa');
							} else if (response.tip=='3') {
								med_icon.classList.add('bi-image', 'text-ciano');
							}

							file.previewElement.querySelector('.dz-upload').style.display='none';
						}
					});
					
					</script>

					<div class='row row-cols-2 row-cols-md-3' id='preview'></div>

				</div>

		";
		?>
		</div>
		</div>

	</body>
</html>