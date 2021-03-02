		<?php 
		require('head.php');
		$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["id"])."'"));	#Informações Projeto
		$pro_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$pro["uti"]."'"));				#Informações Utilizador do projeto
		if ($pro_uti['id']==$uti['id']){ $per = 1; $vis = 1; }	#Dar premissão e visualização
		if ($pro['pri']==0){ $vis = 1; }						#Dar visualização
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<?php
		if ($pro AND $vis){ #Se o projeto existir e for visivel.

			echo "
			<div class='p-xl-5 p-4 offset-xl-3 col-xl-6'>
				<h1>".$pro['tit']."</h1>
			";
			
			if ($per){
				echo "
				<section class='text-right'>
					
					<button class='btn btn-dark bg-".$pro['cor']."' text-light' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Configurações
					<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#sliders'/></svg>
					</button>
					<button class='btn btn-dark bg-".$pro['cor']."' id='criar_sec'>Nova secção
					<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#plus-circle'/></svg>
					</button>
					<script>
					$('#criar_sec').click(function(){
						$.ajax({
							url: 'pro/criar_sec.php?pro=+".$_GET["id"]."',
							success: function(result) {
								if (result){
									alert('Ocorreu um erro.');
								} else {
									location.reload();
								}
							},
							error: function(){
								alert('Ocorreu um erro.');
							}
						});
					});
					</script>
					<br>
				</section>
			</div>

			<div class='p-0 my-0 offset-xl-3 col-xl-6'>
				<section id='collapseExample' class='my-2 p-xl-5 p-4 bg-dark text-light collapse'>
					<h3>Configurações</h3>
				
					<text class='h5'>Cor tema</text>
					<div id='pro_cor' class='form-check'>
					";
					for ($n = 0; $n <= 6; $n++) {
						echo "<input ";
						if ($n==$pro['cor']){ echo "checked ";}
						echo "class='form-check-input cor".$n."' type='radio' name='pro_cor' id='cor".$n."'>
						<label class='form-check-label mr-4' for='cor".$n."'>";
						switch ($n){
							case 0: echo "Preto"; break;
							case 1: echo "Azul"; break;
							case 2: echo "Verde Água"; break;
							case 3: echo "Verde"; break;
							case 4: echo "Amarelo"; break;
							case 5: echo "Vermelho"; break;
							case 6: echo "Roxo"; break;
						}
						echo "</label><br>";
					}
					echo "
					</div>
					<script>
					$(':radio').change(function(){
						if (this.id.includes('cor')){
							$.ajax({
								url: 'pro/pro_cor.php?pro=".$_GET["id"]."&cor='+this.id,
								success: function(data){
									console.log(data);
								},
								error: function(){
									alert('Ocorreu um erro');
								}
							});
						}
					});   
					</script>
				</section>
			";
			} else {
				echo "</div>
				<div class='p-0 my-0 offset-xl-3 col-xl-6'>";
			}

				$pesquisa = "SELECT * FROM pro_sec WHERE pro=".$pro['id']." ORDER BY id DESC";
                if ($resultado = $bd->query($pesquisa)) {
					$num_sec = $resultado->num_rows;
                    while ($campo = $resultado->fetch_assoc()) {
						if ($campo['ati']==1){
							echo "<section class='my-2 p-xl-5 p-4 bg-".$pro['cor']."' id='sec_".$num_sec."'>
							<text class='h5' id='tit_".$num_sec."'>";
							if (!$campo['tit_ati']){
								echo "‎ ";
							} else if ($campo['tit']){
								echo $campo['tit'];
							} else {
								echo "Secção ".$num_sec;
							}
							echo "</text>";

							if ($per){
								echo "
								<div class='float-right'>
									<button class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Alterar título'>
										<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#pencil'/></svg>
									</button>
									<button class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Visíbilidade do título' onclick=\"tit_ati('".base64_encode($campo['id'])."',".$num_sec.")\">
										<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#";
										if ($campo['tit_ati']==0){ echo "eye"; } else { echo "eye-slash"; }
										echo "'/></svg>
									</button>
									<button class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Eliminar' onclick=\"apagar_sec('".base64_encode($campo['id'])."',".$num_sec.")\">
										<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#trash'/></svg>
									</button>
								</div>";
							}

							echo "
							<div id='editorjs'></div>
							<script>
								import EditorJS from '@editorjs/editorjs';
								const editor = new EditorJS('editorjs');
							</script>
							</section>";
						}
						$num_sec--;

                    } 
					$resultado->free();
					if ($per){
						echo "
						<script>
						function tit_ati(id,num){
							$.ajax({
								url: 'pro/sec_tit_ati.php?sec='+id,
								success: function(result) {
									if (result){
										$('#tit_'+num).text(result);
									} else {
										$('#tit_'+num).text('Secção '+num);
									}
								},
								error: function(){
									alert('Ocorreu um erro. Secção ID: '+id);
								}
							});
						}
						function apagar_sec(id,num){
							$('#sec_'+num).remove();
							$.ajax({
								url: 'pro/apagar_sec.php?id='+id,
								success: function(result) {
									if (result){
										alert(result);
									}
								},
								error: function(){
									alert('Ocorreu um erro. Secção ID: '+id);
								}
							});
						}
						</script>";
					}
				}
				
			echo "</div>";

		} else {
			echo "<h2 class='my-4 text-center'>Não é possivel localizar o projeto ☹️</h2>";
		}
		?>
		</div>
	</body>
</html>