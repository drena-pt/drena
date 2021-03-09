		<?php
		require('head.php');
		$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["id"])."'"));	#Informações Projeto
		$pro_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$pro["uti"]."'"));				#Informações Utilizador do projeto
		if ($pro_uti['id']==$uti['id']){ $per = 1; $vis = 1; }	#Dar premissão e visualização
		if ($pro['pri']==0){ $vis = 1; }						#Dar visualização
		$vis = 1; 
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
					<button class='btn btn-dark text-light' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
						Configurações <svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#sliders'/></svg>
					</button>

					<button class='btn btn-dark bg-".$pro['cor']."' id='criar_sec'>
						Nova secção <svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#plus-circle'/></svg>
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
				<section id='collapseExample' class='my-2 bg-dark text-light collapse'>
					<div class='p-xl-5 p-4'>
						<h3>Configurações</h3>
					
						<text class='h5'>Cor tema</text>
						<div id='pro_cor' class='form-check'>
						";
						for ($n = 0; $n <= 6; $n++) {
							echo "<input ";
							if ($n==$pro['cor']){ echo "checked ";}
							echo "class='form-check-input cor".$n."' type='radio' name='pro_cor' id='bg-".$n."'>
							<label class='form-check-label mr-4' for='bg-".$n."'>";
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
							if (this.id.includes('bg-')){
								$('*[id*=sec]').removeClass('bg-0 bg-1 bg-2 bg-3 bg-4 bg-5 bg-6');
								$('*[id*=sec]').addClass(this.id);
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
					</div>
				</section>
			";
			} else {
				echo "
					<div class='row mb-1'>
						<div class='col-auto pr-0 text-center'>
							<a href='/perfil?uti=".$pro_uti['nut']."'><img src='fpe/".base64_encode($pro_uti["fot"])."' class='rounded-circle' width='40'></a>
						</div>
						<div class='col d-flex'>
							<span class='justify-content-center align-self-center'>Criado por: ".$pro_uti['nut']."</span>
						</div>
					</div>
				</div>
				<div class='p-0 my-0 offset-xl-3 col-xl-6'>
				";
			}
			#Secções
			$pesquisa = "SELECT * FROM pro_sec WHERE pro=".$pro['id']." AND ati='1' ORDER BY id DESC";
            if ($resultado = $bd->query($pesquisa)) {
				$num_sec = $resultado->num_rows;
                while ($campo = $resultado->fetch_assoc()) {
					if ($per OR $campo['vis']==1){
						echo "
						<section class='my-2 p-xl-5 p-4 bg-".$pro['cor']."' id='sec_".$num_sec."'>";

						if ($per){
							echo "
							<div class='d-flex flex-row-reverse mb-3'>
								<div>
									<button class='btn btn-light ml-1' data-toggle='tooltip' data-placement='bottom' data-original-title='Visibilidade' onclick=\"visibilidade('".base64_encode($campo['id'])."',".$num_sec.")\">
										<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#eye'/></svg>
									</button>

									<button onclick=\"window.open('editar_sec.php?id=".base64_encode($campo['id'])."','_blank')\"  class='btn btn-light ml-1' data-toggle='tooltip' data-placement='bottom' data-original-title='Editar texto'>
										<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#pencil'/></svg>
									</button>

									<button class='btn btn-light ml-1' data-toggle='tooltip' data-placement='bottom' data-original-title='Eliminar' onclick=\"apagar_sec('".base64_encode($campo['id'])."',".$num_sec.")\">
										<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#trash'/></svg>
									</button>

									<div class='btn-group ml-1' role='group' aria-label='Basic example'>
										<button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Mover para baixo'>
											<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#arrow-down'/></svg>
										</button>
										<button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Mover para cima'>
											<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#arrow-up'/></svg>
										</button>
									</div>
								</div>


								<text class='my-auto mr-auto h5 mb-3' id='tit_".$num_sec."'>
									Secção ".$num_sec;
									if ($campo['vis']==0){echo" (invisível)";}
									echo "
								</text>
							</div>
							<hr>
							";
						}
						echo "
							<div class='texto' id='tex_".$campo['id']."'>
								";
								if ($campo['tex']){
									echo "
									<script>
										var edjsParser = edjsHTML();
										$('#tex_".$campo['id']."').html(edjsParser.parse(".$campo['tex']."));
									</script>
									";
								}
								echo "
							</div>
						</section>
						";
						$num_sec--;
					}
                } 
				$resultado->free();
				if ($per){
					echo "
					<script>
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
					function visibilidade(id,num){
						$.ajax({
							url: 'pro/sec_visibilidade.php?sec='+id,
							success: function(result) {
								if (result==='true'){
									$('#tit_'+num).text('Secção '+num);
								} else {
									$('#tit_'+num).text('Secção '+num+' (invisível)');
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