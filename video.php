		<?php 
		require('head.php');
		$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));

		if ($med){
			if ($med['tit']){$med_tit = $med['tit'];} else {$med_tit = $med['nom'];}															#Definir título do vídeo
			$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));									#Utilizador dono do vídeo
			$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$_GET["id"]."' AND uti='".$uti['id']."';"));	#Informações do gosto
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<?php
			function tempoHumano($time){
			
				$time = time() - $time; // to get the time since that moment
				$time = ($time<1)? 1 : $time;
				$tokens = array (
					31536000 => 'ano',
					2592000 => 'mês',
					604800 => 'semana',
					86400 => 'dia',
					3600 => 'hora',
					60 => 'minuto',
					1 => 'segundo'
				);
			
				foreach ($tokens as $unit => $text) {
					if ($time < $unit) continue;
					$numberOfUnits = floor($time / $unit);
					return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
				}
			
			}
			echo "
			<div class='p-0 my-0 offset-xl-3 col-xl-6'>

				<h3><br></h3>

				<section class='bg-dark'>
					
				<div class='mw-100'>
					<div style='position:relative;padding-bottom:56.25%;'>
						<iframe style='position:absolute;top:0;left:0;width:100%;height:100%;' src='/embed?id=".$_GET['id']."&titulo=0'></iframe>
					</div>
				</div>

					<div class='p-4'>
						<div class='d-flex flex-row-reverse mb-3'>";
						if ($uti['id']==$med_uti['id']){
							echo "
							<span data-toggle='modal' data-target='#modal_alerar_tit'>
								<button class='btn btn-light ms-2' data-toggle='tooltip' data-placement='bottom' data-original-title='Alterar título'>
										<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#input-cursor-text'/></svg>
								</button>
							</span>
							<!-- Modal -->
							<div class='modal fade' id='modal_alerar_tit' tabindex='-1' role='dialog' aria-labelledby='modal_alerar_tit_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark'>
										<form action='pro/video.php?ac=titulo&id=".$_GET['id']."' method='post'>
											<div class='modal-header'>
												<h5 class='modal-title' id='modal_alerar_tit_label'>Alterar título</h5>
												<button type='button' class='close text-light' data-dismiss='modal' aria-label='Close'>
													<span aria-hidden='true'>&times;</span>
												</button>
											</div>
											<div class='modal-body'>
												<input type='text' class='form-control' name='tit' placeholder='Título' value='".$med_tit."'>
											</div>
											<div class='modal-footer'>
												<button type='button' class='btn btn-light' data-dismiss='modal'>Fechar</button>
												<button type='submit' class='btn btn-primary'>Alterar</button>
											</div>
										</form>
									</div>
								</div>
							</div>

							<button onclick=\"window.open('pro/video.php?ac=eliminar&id=".$_GET['id']."','_self')\" class='btn btn-light ml-1' data-toggle='tooltip' data-placement='bottom' data-original-title='Eliminar vídeo'>
									<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#trash'/></svg>
							</button>
							";
						}
							echo "
							<text class='h5 my-auto me-auto'>".$med_tit."</text>
						</div>
						<section class='mt-auto'>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<a href='/perfil?uti=".$med_uti['nut']."'><img src='fpe/".base64_encode($med_uti["fot"])."' class='rounded-circle' width='40'></a>
								</div>
								<div class='col d-flex'>
									<span class='justify-content-center align-self-center'>Publicado por: ".$med_uti['nut']."</span>
								</div>
							</div>
							<!--<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#bar-chart'/></svg>
								</div>
								<div class='col'>
									 visualizações
								</div>
							</div>-->
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg onclick='gosto()' class='bi' style='cursor:pointer;' width='1em' height='1em' fill='currentColor'>
										<use id='botao_gosto' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up-fill' "; if(!$med_gos){echo"hidden";} echo"/>
										<use id='botao_naogosto' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up' "; if($med_gos){echo"hidden";} echo"/>
									</svg>
								</div>
								<div class='col' >
									<span id='texto_gostos'>".$med['gos']."</span> gostos
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#calendar4-week'/></svg>
								</div>
								<div class='col'>
									há ".tempoHumano(strtotime($med['den']))."
								</div>
							</div>
						</section>
					</div>

				</section>

			</div>
			";
			if ($uti){
				echo "
				<script>
				function gosto(){
					$.ajax({
						url: 'pro/med_gos.php?id=".$med['id']."',
						success: function(result) {
							var gostos = +$('#texto_gostos').text();
							if (result==='true'){
								$('#botao_gosto').removeAttr('hidden');
								$('#botao_naogosto').attr('hidden', true);
								$('#texto_gostos').text(gostos+1);
							} else {
								$('#botao_gosto').attr('hidden', true);
								$('#botao_naogosto').removeAttr('hidden');
								$('#texto_gostos').text(gostos-1);
							}
						},
						error: function(){
							alert('Ocorreu um erro.');
						}
					});
				}
				</script>
				";
			} else {
				echo "
				<script>
				function gosto(){
					window.open('/entrar','_self');
				}
				</script>
				";
			}
		?>
		</div>
	</body>
</html>