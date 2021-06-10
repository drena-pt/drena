		<?php 
		require('head.php');
		$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."' AND tip='1'"));

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
			if (!$med){
				echo "<h2 class='my-5 text-center'>"._('Vídeo não encontrado!')." 😵</h2>‍";
				exit;
			}
			function tempoPassado($ptime){
				$etime = time() - $ptime; # obtem o tempo que passou desde a data
				if ($etime < 1){ return '0 '._('segundos'); }
				$a = array( 31536000 => _('ano'),
							2592000 => _('mês'),
							604800 => _('semana'),
							86400 => _('dia'),
							3600 => _('hora'),
							60 => _('minuto'),
							1 => _('segundo')
							);
				$a_plural = array(
							_('ano') => _('anos'),
							_('mês') => _('mêses'),
							_('semana') => _('semanas'),
							_('dia') => _('dias'),
							_('hora') => _('horas'),
							_('minuto') => _('minutos'),
							_('segundo') => _('segundos')
							);
				foreach ($a as $secs => $str){
					$d = $etime / $secs;
					if ($d >= 1){
						$r = floor($d);
						return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
					}
				}
			}
			echo "
			<div class='p-0 my-0 offset-xl-3 col-xl-6 mt-0 mt-xl-4'>

				<section class='bg-dark shadow'>
					
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
								<button class='btn btn-light ms-2' data-toggle='tooltip' data-placement='bottom' data-original-title='"._('Alterar título')."'>
										<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#input-cursor-text'/></svg>
								</button>
							</span>
							<span data-toggle='modal' data-target='#modal_eliminar_med'>
								<button class='btn btn-light ml-1' data-toggle='tooltip' data-placement='bottom' data-original-title='"._('Eliminar vídeo')."'>
									<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#trash'/></svg>
								</button>
							</span>

							<!-- Modal -->
							<div class='modal fade' id='modal_alerar_tit' tabindex='-1' role='dialog' aria-labelledby='modal_alerar_tit_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<form action='pro/video.php?ac=titulo&id=".$_GET['id']."' method='post'>
											<div class='modal-header'>
												<h2 class='modal-title' id='modal_alerar_tit_label'>"._('Alterar título')."<br></h2><br>
											</div>
											<div class='modal-body'>
												<input type='text' class='form-control' name='tit' placeholder='"._('Título')."' value='".$med_tit."'>
											</div>
											<div class='modal-footer text-end'>
												<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
												<button type='submit' class='btn btn-primary text-light'>"._('Alterar')."</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- Modal Eliminar áudio-->
							<div class='modal fade' id='modal_eliminar_med' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_med_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<div class='modal-header'>
											<h2 class='modal-title' id='modal_eliminar_med_label'>"._('Eliminar vídeo')."<br></h2><br>
										</div>
										<div class='modal-body'>
											<text><span class='h5'>".$med_tit."</span><br>"._('Esta ação é irreversível!')."</text>
										</div>
										<div class='modal-footer text-end'>
											<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
											<a href='pro/video.php?ac=eliminar&id=".$_GET['id']."' role='button' class='btn btn-vermelho text-light'>"._('Eliminar')."</a>
										</div>
									</div>
								</div>
							</div>
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
									<span class='justify-content-center align-self-center'>"._('Publicado por')." ".$med_uti['nut']."</span>
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
									<span id='texto_gostos'>".$med['gos']."</span> "._('gostos')."
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#calendar4-week'/></svg>
								</div>
								<div class='col'>
									".sprintf(_('há %s'),tempoPassado(strtotime($med['den'])))."
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