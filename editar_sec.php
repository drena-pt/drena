		<?php
		require('head.php');
		$pro_sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='".base64_decode($_GET["id"])."'"));	#Informações da secção
		$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".$pro_sec['pro']."'"));						#Informações do projeto
		$pro_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$pro["uti"]."'"));						#Informações Utilizador do projeto
		if ($pro['uti']==$uti['id']){	#Dar premissão
			$per = 1;
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
			<?php
			if ($pro_sec AND $per){ #Se a secção existir e houver permissão
				echo "
				<div class='p-0 my-0 offset-xl-3 col-xl-6'>
					<section class='my-2 p-xl-5 p-4 bg-".$pro['cor']."'>
						<div class='bg-light text-dark mb-4'>
							<div id='editorjs'></div>
						</div>
						<div class='text-right'>
							<button class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Guardar' onclick=\"guardar()\">
								Guardar	<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#save'/></svg>
							</button>
						</div>

						<script>
							const editor1 = new EditorJS({
								";
								if ($pro_sec['tex']){
									echo "data: ".($pro_sec['tex']).",";
								}
								echo "
								holder: 'editorjs',
								logLevel: 'ERROR',
								tools: {
									header: {
									  class: Header,
									  config: {
										placeholder: 'Enter a header',
										levels: [2, 3, 4],
										defaultLevel: 3
									  }
									},
									image: {
									  class: ImageTool,
									  config: {
										endpoints: {
										  byFile: 'upload_imagem.php?por=ficheiro', // Your backend file uploader endpoint
										  byUrl: 'upload_imagem.php?por=link', // Your endpoint that provides uploading by Url
										}
									  }
									},
									embed: Embed
								}
							});

							function guardar(){
								editor1.save().then((outputData) => {
									$.post('guardar_projeto.php',
									{
									id: '".base64_decode($_GET["id"])."',
									texto: JSON.stringify(outputData)
									},
									function(data){
										alert('Guardado com suecesso!');
									});
								}).catch((error) => {
								console.log('Saving failed: ', error)
								});
							}
						</script>
					</section>
				</div>";
			}
			?>
		</div>
	</body>
</html>