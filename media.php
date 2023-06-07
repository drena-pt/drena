<?php
$site_tit = 'off';
require('head.php');
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));

#Se houver uma sessão iniciada carrega o script da API
if ($uti){
	echo "<script src='/js/api.min.js'></script>";
}

function tempoPassado($ptime){
	$etime = time() - $ptime; # Obtem o tempo que passou desde a publicação
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
				_('mês') => _('meses'),
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

if ($med){
	$med_tit = $med['tit'];#Definir título
	$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));									# Utilizador dono
	$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$med["id"]."' AND uti='".$uti['id']."';"));	# Informações do gosto do utilizador logado

	echo "
	<!-- Tags de motor de pequisa -->
	<meta property='og:title' content='".$med_tit."'/>
	<meta property='og:description' content='".$med_uti['nut'].", ".sprintf(_('há %s'),tempoPassado(strtotime($med['den']))).", ".$med['gos']." "._('gostos')."'/>
	<meta property='og:url' content='".$url_site."m/".$med['id']."'/>
	<meta property='og:image' content='".$url_media."thumb/".$med['thu'].".jpg'/>
	<!-- Image larger -->
	<meta name='twitter:card' content='summary_large_image'>

	<title>".$med_tit." - drena</title>
	";

	#Se for um vídeo
	if ($med['tip']==1){
		$med_file = basename(glob($dir_media."ori/".$med['id']."*")[0]);
		$med_ori = $url_media."ori/".$med_file;

		echo "<meta property='og:type' content='video' />";
		if ($med['est']==3){ #Estado 3 (comprimido).
			echo "<meta property='og:video' content='".$url_media."comp/".$med["id"].".mp4' />";					
		} else if ($med['est']==5){ #Estado 5 (convertido).
			echo "<meta property='og:video' content='".$url_media."conv/".$med["id"].".mp4' />";					
		} else { #Todos os outros estados.
			echo "<meta property='og:video' content='".$med_ori."' />";
		}
	}
}
?>
</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<?php
		if (!$med OR ($med['pri']==1 AND $med['uti']!=$uti['id'])){
			echo "<h2 class='my-5 text-center'>"._('Média não encontrada!')." 😵</h2>‍";
			exit;
		} else {
			echo "<div class='p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3'>";

				echo "<section class='bg-dark shadow'>";
					switch ($med['tip']){
						case 1: # Vídeo
							$t_eliminar = _('Eliminar vídeo');
							$t_cor = 'primary';
							echo "
							<div class='mw-100'>
								<div style='position:relative;padding-bottom:56.25%;'>
									<iframe id='iframe_med' style='position:absolute;top:0;left:0;width:100%;height:100%;' src='/embed?id=".$med['id']."&titulo=0'></iframe>
								</div>
							</div>";
							
							if ($uti['id']==$med_uti['id']){
								if ($med['est']=='1'){
									echo "
									<div class='p-xl-5 p-4 bg-amarelo'>
										<text class='h5 my-auto me-auto'><i class='bi bi-exclamation-triangle-fill'></i> "._('Aviso').":</text>
										<div class='row'>
   											<div class='col-md-8'>
												<p>"._('A qualidade do vídeo é elevada, e pode comprometer a visualização em conexões mais lentas. Comprima o vídeo.')."</p>
											</div>
    										<div class='col-md-4 text-end'>
												<button id='btn_comprimir' class='btn btn-light text-dark'>"._('Comprimir vídeo')." <i class='bi bi-gear'></i></button>
											</div>
										</div>
									</div>
									<script>
									$('#btn_comprimir').click(function() {
										result = api('med',{'med':'".$med['id']."','ac':'comprimir'});
										if (result['est']=='sucesso'){
											console.log(result);
										}
									});
									</script>
									";
								} else if ($med['est']=='2'){
									echo "<div class='p-xl-5 p-4 bg-primary'><text class='h5 my-auto me-auto'><i class='bi bi-info-circle'></i> "._('O vídeo está a ser processado...')."</text></div>";
								}
							}
							break;
						case 2: # Áudio
							$t_eliminar = _('Eliminar áudio');
							$t_cor = 'rosa';
							echo "<iframe id='iframe_med' height='180px' class='w-100' src='/embed?id=".$med['id']."&titulo=0'></iframe>";
							break;
						case 3: # Imagem
							$t_eliminar = _('Eliminar imagem');
							$t_cor = 'ciano';
							echo "
							<iframe id='iframe_med' style='min-height:50vh;' class='w-100' src='/embed?id=".$med['id']."'></iframe>";
							break;
					}
					echo "
					<div class='p-xl-5 p-4'>
						<div class='row mb-3'>
							<div class='col-12 col-md d-flex'>
								<text id='med_tit' class='h5 my-auto'>".$med_tit."</text>
							</div>

							<div class='col-md my-md-0 my-2 d-flex flex-md-row-reverse flex-row'>
							";

						if ($uti['id']==$med_uti['id']){ # Botões de gestão de média, para o utilizador dono
							if ($med['tip']=='1' OR $med['tip']=='2'){ # Caso seja um vídeo ou um áudio, para mudar thumbnail
								echo "
								<span>
									<label for='input_thu' role='button' class='btn btn-light me-1 my-auto'>
										<span id='thu_carregar' data-toggle='tooltip' data-placement='bottom' data-original-title=\""._('Alterar miniatura')."\">
											<i class='bi bi-file-earmark-image'></i>
										</span>
										<span id='thu_a_carregar' data-toggle='tooltip' data-placement='bottom' data-original-title=\""._('A carregar...')."\" style='display:none;'>
											<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>
										</span>
									</label>

								</span>

								<form hidden enctype='multipart/form-data'>
									<input type='file' accept='image/*' id='input_thu'/>
									<input type='submit'/>
								</form>

								<script>
								$('#input_thu').change(function() {
									$('#thu_a_carregar').show();
									$('#thu_carregar').hide();
									var form_thu = new FormData();
									var thu = $(this)[0].files[0];
									form_thu.append('thu', thu);
									form_thu.append('med', '".$med['id']."');
									setTimeout(function(){
										result = api('med_thu', form_thu, false, false);
										console.debug(result);
										$('#thu_a_carregar').hide();
										$('#thu_carregar').show();
										if (result['est']=='sucesso'){
											$('#iframe_med').attr('src', function(i, val){ return val; });
										}
									}, 800);
								});
								</script>
								";
							} else if ($med['tip']=='3'){ # Caso seja uma imagem, para adicionar a um album
								echo "<span data-toggle='modal' data-target='#modal_albuns'>
									<button class='btn btn-light me-1 my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title=\""._('Adicionar a um álbum')."\">
										<i class='bi bi-collection'></i>
									</button>
								</span>

								<!-- Modal Álbuns -->
								<div class='modal fade' id='modal_albuns' tabindex='-1' role='dialog' aria-labelledby='modal_albuns_label' aria-hidden='true'>
									<div class='modal-dialog' role='document'>
										<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
											<form action='#' method='post'>
												<div class='modal-header'>
													<h2 class='modal-title' id='modal_albuns_label'>"._('Adicionar a um álbum')."<br></h2><br>
												</div>
												<div class='modal-body'>
													";
													$pesquisa = "SELECT * FROM med_alb WHERE uti='".$uti['id']."' ORDER BY id DESC";
													if ($resultado = $bd->query($pesquisa)){
														echo "<ul class='list-group list-group-flush'>";
														while ($campo = $resultado->fetch_assoc()){
															#Define o nome a aparecer
															if (!$campo['tit']){$alb_tit=sprintf(_('Álbum de %s'),$uti['nut']);}else{$alb_tit=$campo['tit'];}
															$alb_num_med = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med WHERE alb='".$campo['id']."';"));

															#Se a média estiver nesse album
															if ($med['alb']==$campo['id']){
																echo "
																<a href='/album?id=".base64_encode($campo['id'])."' class='list-group-item bg-transparent px-0'>
																<section class='p-4 bg-light bg-cover text-primary rounded-xl shadow d-flex justify-content-between align-items-center' style='background-image: linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.8)), url(\"".$url_media."thumb/".$campo['thu'].".jpg\");'>
																		<h5 class='m-0'>".$alb_tit."</h5>
																		<span class='badge rounded-pill bg-primary text-light'>".$alb_num_med."</span>
																	</section>
																</a>";
															} else {
																echo "
																<li onclick='med_alb(\"".$campo['id']."\")' role='button' class='list-group-item bg-transparent px-0'>
																	<section class='p-4 bg-light bg-cover text-dark rounded-xl shadow d-flex justify-content-between align-items-center' style='background-image: linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.8)), url(\"".$url_media."thumb/".$campo['thu'].".jpg\");'>
																		<h5 class='m-0'>".$alb_tit."</h5>
																		<span class='badge rounded-pill bg-dark text-light'>".$alb_num_med."</span>
																	</section>
																</li>";
															}

														}
														$resultado->free();
														echo "
														<li onclick='criar_alb()' role='button' class='list-group-item bg-transparent px-0'>
															<section class='p-2 px-4 bg-light text-dark rounded-xl shadow d-flex justify-content-between align-items-center'>
																<h5 class='m-0'>"._('Criar álbum')."</h5><h5 class='bi bi-plus-circle'></h5>
															</section>
														</li>
														</ul>";
													}
													echo "
												</div>
												<div class='modal-footer text-end'>
													<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<script>
								function med_alb(alb){
									result = api('med_alb',{'alb': alb,'med': '".$med['id']."','ac': 'med'});
									if (result.est=='true'){
										window.location.href = '/album?id='+btoa(alb);
									}
								}

								function criar_alb(){
									result = api('med_alb',{'med':'".$med['id']."','ac':'criar'});
									if (result.est=='sucesso'){
										window.location.href = '/album?id='+result.alb;
									}
								}
								</script>
								";
							}
							echo "
							<span data-toggle='modal' data-target='#modal_alerar_tit'>
								<button class='btn btn-light me-1 my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title='"._('Alterar título')."'>
									<i class='bi bi-input-cursor-text'></i>
								</button>
							</span>
							<span data-toggle='modal' data-target='#modal_eliminar_med'>
								<button class='btn btn-light me-1 my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title=\"".$t_eliminar."\">
									<i class='bi bi-trash'></i>
								</button>
							</span>";

							if ($med['pri']==1){
								$t_privado = _('Tornar público');
								$i_privado = "lock";
								$bg_privado = "primary";
							} else {
								$t_privado = _('Tornar privado');
								$i_privado = "unlock";
								$bg_privado = "light";
							}
							echo "
							<span>
								<button id='privar' class='btn btn-".$bg_privado." me-1 my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title=\"".$t_privado."\">
									<i id='icon_privar' class='bi bi-".$i_privado."'></i>
								</button>
							</span>

							<!-- Modal Alterar título -->
							<div class='modal fade' id='modal_alerar_tit' tabindex='-1' role='dialog' aria-labelledby='modal_alerar_tit_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<form id='form_titulo'>
											<div class='modal-header'>
												<h2 class='modal-title' id='modal_alerar_tit_label'>"._('Alterar título')."<br></h2><br>
											</div>
											<div class='modal-body'>
												<input id='input_tit' type='text' class='form-control' name='tit' placeholder='"._('Título')."' autocomplete='off' value='".$med_tit."'>
											</div>
											<div class='modal-footer text-end'>
												<button id='fechar_titulo' type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
												<button type='submit' class='btn btn-".$t_cor." text-light'>"._('Alterar')."</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- Modal Eliminar -->
							<div class='modal fade' id='modal_eliminar_med' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_med_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<div class='modal-header'>
											<h2 class='modal-title' id='modal_eliminar_med_label'>".$t_eliminar."<br></h2><br>
										</div>
										<div class='modal-body'>
											<text><span id='med_tit' class='h5'>".$med_tit."</span><br>"._('Esta ação é irreversível!')."</text>
										</div>
										<div class='modal-footer text-end'>
											<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
											<button id='eliminar_med' type='button' class='btn btn-vermelho text-light'>"._('Eliminar')."</a>
										</div>
									</div>
								</div>
							</div>

							<script>
							$('#privar').click(function() {
								result = api('med',{'med':'".$med['id']."','ac':'privar'});
								if (result['est']=='publico'){
									$('#privar').tooltip('hide')
										.attr('data-original-title', '"._('Tornar privado')."')
										.tooltip('show');
									$('#privar').removeClass('btn-primary').addClass('btn-light');
									$('#icon_privar').removeClass('bi-lock').addClass('bi-unlock');
								} else if (result['est']=='privado'){
									$('#privar').tooltip('hide')
										.attr('data-original-title', '"._('Tornar público')."')
										.tooltip('show');
									$('#privar').removeClass('btn-light').addClass('btn-primary');
									$('#icon_privar').removeClass('bi-unlock').addClass('bi-lock');
								}
							});
							
							$('#eliminar_med').click(function() {
								result = api('med',{'med':'".$med['id']."','ac':'eliminar'});
								if (result['est']=='eliminado'){
									window.location.href = '/';
								}
							});

							$('#form_titulo').on('submit', function(e) {
								e.preventDefault();
								var tit = $('#input_tit').val();
								result = api('med',{'med':'".$med['id']."','ac':'titulo','tit':tit});
								if (result['est']=='sucesso'){
									$('[id=\"med_tit\"]').each(function(){
										$(this).html(tit);
									});
									$('#fechar_titulo').click();
								}
							});

							$('#input_tit').on('input', function() { 
								$('#med_tit').text($(this).val());
							});
							</script>
							";
						} else if ($uti['car']==2){ #Ferramenta do moderador
							echo "
							<span data-toggle='modal' data-target='#modal_moderador'>
								<button class='btn btn-ciano text-light me-1 my-auto'>
									"._('Moderar')." ";
									if ($med['nmo']==0){
										echo "<i class='bi bi-clipboard'></i>";
									} else {
										echo "<i class='bi bi-clipboard-check'></i>";
									}
									echo "
								</button>
							</span>
							<!-- Modal Moderador -->
							<div class='modal fade' id='modal_moderador' tabindex='-1' role='dialog' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<div class='modal-header'>
											<h2 class='modal-title'>"._('Moderar')."</h2>
										</div>
										<div class='modal-body'>
											<text class='h5'>".$med_tit."</text><br><br>
											<div id='caixa_pedido_mod' style='display:none' class='row my-4'>
												<div class='col-auto pe-0 text-center'>
													<a id='url_pedido_mod'><img id='img_pedido_mod' class='rounded-circle' width='40'></a>
												</div>
												<div class='col d-flex'>
													<span class='justify-content-center align-self-center'>
														"._('Pedido pelo moderador')." <span id='nut_pedido_mod'></span>:<br>
														<span id='texto_pedido_mod'></span>
													</span>
												</div>
											</div>
											<button id='btn_discordo' style='display:none' class='me-1 btn btn-ciano text-light'>"._('Discordo')."</button>
											<button id='btn_concordo' style='display:none' class='btn btn-ciano text-light'>"._('Concordo')."</button>
											<button id='btn_sensivel' style='display:none' class='btn btn-ciano text-light'>"._('Definir como contéudo sensível')." <i class='bi bi-eye-slash'></i></button>
											<button id='btn_inaceitavel' style='display:none' class='btn btn-vermelho text-light'>"._('Reportar como contéudo inaceitável')." <i class='bi bi-x-octagon'></i></button>
										</div>
										<div class='modal-footer text-end'>
											<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
										</div>
									</div>
								</div>
							</div>

							<script>
							function moderar(mod_ac){
								if (mod_ac === undefined){ result = api('med',{'med':'".$med['id']."', 'ac':'mod'});
								} else { result = api('med',{'med':'".$med['id']."', 'ac':'mod', 'mod':mod_ac}); }
								console.debug(result);

								if(result['ac_mod'].indexOf(0) !== -1){ $('#btn_discordo').attr('disabled', true);
								}else{ $('#btn_discordo').attr('onclick', 'moderar(0)'); }

								if(result['ac_mod'].indexOf(1) !== -1){ $('#btn_sensivel').attr('disabled', true);
								} else { $('#btn_sensivel').attr('onclick', 'moderar(1)'); }

								if(result['ac_mod'].indexOf(2) !== -1){ $('#btn_inaceitavel').attr('disabled', true);
								} else { $('#btn_inaceitavel').attr('onclick', 'moderar(2)'); }

								if (result['nmo']<=2){ $('#btn_inaceitavel').show();
								} else { $('#btn_inaceitavel').hide(); }

								if (result['nmo']==0){ $('#btn_sensivel').show();
								} else { $('#btn_sensivel').hide(); }

								//Se houver um pedido de um moderador ativo
								if (result['nmo']!==0 && result['nmo']!==2){
									
									if (result['nmo']==1){
										if(result['ac_mod'].indexOf(1) !== -1){
											$('#btn_concordo').attr('disabled', true);
										}else{
											$('#btn_concordo').attr('onclick', 'moderar(1)');
										}
										texto_pedido_mod = '"._('Definir como contéudo sensível')."';
									} else {
										if(result['ac_mod'].indexOf(2) !== -1){
											$('#btn_concordo').attr('disabled', true);
										}else{
											$('#btn_concordo').attr('onclick', 'moderar(2)');
										}
										if (result['nmo']==3){
											texto_pedido_mod = '"._('Definir como contéudo inaceitável')."';
										} else if (result['nmo']==4){
											texto_pedido_mod = '"._('Eliminar contéudo inaceitável')."';
										}
									}

									$('#btn_discordo').show();
									$('#btn_concordo').show();
									$('#texto_pedido_mod').html(texto_pedido_mod);
									$('#url_pedido_mod').attr('href', '/u/'+result['u_mod_uti']['nut']);
									$('#img_pedido_mod').attr('src', result['u_mod_uti']['fpe']);
									$('#nut_pedido_mod').html(result['u_mod_uti']['nut']);
									$('#caixa_pedido_mod').show();

								} else {
									$('#caixa_pedido_mod').hide();
									$('#btn_discordo').hide();
									$('#btn_concordo').hide();
								}
							}
							moderar();
							</script>
							";
						}
						echo "
							</div>
						</div>

						<section class='mt-auto'>
							<!--<div class='row mb-1'>
								<div class='col-auto pe-0 text-center'>
									<i class='bi bi-bar-chart'></i>
								</div>
								<div class='col'>
									visualizações
								</div>
							</div>-->
							<div class='row mb-1'>
								<div class='col-auto pe-0 text-center'>
									<a href='/u/".$med_uti['nut']."'><img src='".$url_media."fpe/".$med_uti['fpe'].".jpg' class='rounded-circle' width='40'></a>
								</div>
								<div class='col d-flex'>
									<span class='justify-content-center align-self-center'>"._('Publicado por')." ".$med_uti['nut']."</span>
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pe-0 text-center'>
									<span role='button' onclick='gosto()'>
										<i id='botao_gosto' class='bi bi-hand-thumbs-up-fill' ";if(!$med_gos){echo"hidden";}echo"></i>
										<i id='botao_naogosto' class='bi bi-hand-thumbs-up' ";if($med_gos){echo"hidden";}echo"></i>
									</span>
								</div>
								<div class='col' >
									<span id='texto_gostos'>".$med['gos']."</span> "._('gostos')."
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pe-0 text-center'>
									<i class='bi bi-calendar4-week'></i>
								</div>
								<div class='col'>
									".sprintf(_('há %s'),tempoPassado(strtotime($med['den'])))."
								</div>";
								if ($med['nmo']==2){
									echo "<div class='col d-flex flex-row-reverse flex-row text-muted'>
										<i class='bi bi-eye-slash'></i>Sensível
									</div>";
								}
								echo "
							</div>
						</section>
					</div>

				</section>

			</div>
			<div class='col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
			";
			if ($uti){
				$caixa_cometario = "
				<section id='com_\"+com_id+\"' class='my-4 p-xl-5 p-4 bg-light text-dark rounded-xl shadow'>
					<div class='d-flex flex-row-reverse mb-3'>
						<span data-toggle='modal' data-target='#modal_eliminar_com\"+com_id+\"'>
							<button class='btn btn-dark my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title='"._('Eliminar comentário')."'>
								<i class='bi bi-trash'></i>
							</button>
						</span>
						<div class='modal fade' id='modal_eliminar_com\"+com_id+\"' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_com\"+com_id+\"_label' aria-hidden='true'>
							<div class='modal-dialog' role='document'>
								<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
									<div class='modal-header'>
										<h2 class='modal-title' id='modal_eliminar_com\"+com_id+\"_label'>"._('Eliminar comentário')."<br></h2><br>
									</div>
									<div class='modal-body'>
										<text><span class='h5'>\"+com+\"</span><br>"._('Esta ação é irreversível!')."</text>
									</div>
									<div class='modal-footer text-end'>
										<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
										<button onclick='eliminar_com(\"+com_id+\")' type='button' class='btn btn-vermelho text-light' data-dismiss='modal'>"._('Eliminar')."</button>
									</div>
								</div>
							</div>
						</div>
						<text class='h5 my-auto me-auto'>\"+com+\"</text>
					</div>
					<div class='row mb-1'>
						<div class='col-auto pe-0 text-center'>
							<a href='/u/".$uti['nut']."'><img src='".$url_media."fpe/".$uti['fpe'].".jpg' class='rounded-circle' width='40'></a>
						</div>
						<div class='col d-flex'>
							<span class='justify-content-center align-self-center'>".sprintf(_('Comentado por %s'), $uti['nut'])."</span>
						</div>
					</div>
					<div class='row mb-1'>
						<div class='col-auto pe-0 text-center'>
							<i class='bi bi-calendar4-week'></i>
						</div>
						<div class='col'>"._('Agora')."</div>
					</div>
				</section>
				";

				echo "
				<div id='caixa_botao_comentario' class='text-center my-4'>
					<button onclick='abrir_comentario()' class='btn btn-primary'>"._('Adicionar um comentário')."</button>
				</div>
				
				<div style='display:none;' id='caixa_comentario' class='my-4 p-5 bg-primary bg-gradient rounded-xl shadow text-light'>
					<form id='form_comentario'>
						<h2>"._('Adicionar um comentário')."</h2>
						<input id='input_com' type='text' class='form-control' autocomplete='off' name='input_com' placeholder='"._('Comentário')."'>
						<div class='text-end'>
							<button onclick='fechar_comentario()' type='button' class='btn btn-dark'>"._('Fechar')."</button>
							<button type='submit' class='btn btn-light text-primary'>"._('Comentar')."</button>
						</div>
					</form>
				</div>

				<script>
				$('#form_comentario').on('submit', function(e) {
					e.preventDefault();
					var com = $('#input_com').val();
					result = api('med_com',{'med':'".$med['id']."','ac':'criar','com':com});
					if (result){
						fechar_comentario();
						$('#input_com').val('');
						var com_id = result['id'];
						$('#caixa_comentario').after(\"".preg_replace( "/\r|\n/", "", $caixa_cometario)."\");
					}
				});

				function gosto(){
					result = api('med_gos',{'med':'".$med['id']."'});
					$('#texto_gostos').text(result.num);
                    if (result.gos=='true'){
						$('#botao_gosto').removeAttr('hidden');
						$('#botao_naogosto').attr('hidden', true);
                    } else {
						$('#botao_gosto').attr('hidden', true);
						$('#botao_naogosto').removeAttr('hidden');
                    }
				}

				function eliminar_com(com_id){
					setTimeout(function(){
						result = api('med_com',{'ac':'eliminar','id':com_id});
						if (result['est']=='sucesso'){
							$('#com_'+com_id).remove();
						}
					}, 250);
				}

				function fechar_comentario() {
					$('#caixa_botao_comentario').show();
					$('#caixa_comentario').hide();
				}

				function abrir_comentario() {
					$('#caixa_botao_comentario').hide();
					$('#caixa_comentario').show();
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
			$pesquisa_com = "SELECT * FROM med_com WHERE med='".$med['id']."' ORDER by dcr DESC;";
				if ($resultado = $bd->query($pesquisa_com)) {

					while ($campo = $resultado->fetch_assoc()) {
						$com_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo['uti']."'"));
						
						echo "<section id='com_".$campo['id']."' class='my-4 p-xl-5 p-4 bg-light text-dark rounded-xl shadow'>
							<div class='d-flex flex-row-reverse mb-3'>
							";
								if ($com_uti['id']==$uti['id']){ #Modal Eliminar Comentário
									echo "<span data-toggle='modal' data-target='#modal_eliminar_com".$campo['id']."'>
									<button class='btn btn-dark my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title=\""._('Eliminar comentário')."\">
										<i class='bi bi-trash'></i>
									</button>
									</span>
									<div class='modal fade' id='modal_eliminar_com".$campo['id']."' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_com".$campo['id']."_label' aria-hidden='true'>
										<div class='modal-dialog' role='document'>
											<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
												<div class='modal-header'>
													<h2 class='modal-title' id='modal_eliminar_com".$campo['id']."_label'>"._('Eliminar comentário')."<br></h2><br>
												</div>
												<div class='modal-body'>
													<text><span class='h5'>".$campo['tex']."</span><br>"._('Esta ação é irreversível!')."</text>
												</div>
												<div class='modal-footer text-end'>
													<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
													<button onclick='eliminar_com(".$campo['id'].")' type='button' class='btn btn-vermelho text-light' data-dismiss='modal'>"._('Eliminar')."</button>
												</div>
											</div>
										</div>
									</div>
									";
								}
								echo "
								<text class='h5 my-auto me-auto'>".$campo['tex']."</text>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pe-0 text-center'>
									<a href='/u/".$com_uti['nut']."'><img src='".$url_media."fpe/".$com_uti['fpe'].".jpg' class='rounded-circle' width='40'></a>
								</div>
								<div class='col d-flex'>
									<span class='justify-content-center align-self-center'>".sprintf(_('Comentado por %s'), $com_uti['nut'])."</span>
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pe-0 text-center'>
									<i class='bi bi-calendar4-week'></i>
								</div>
								<div class='col'>
									".sprintf(_('há %s'),tempoPassado(strtotime($campo['dcr'])))."
								</div>
							</div>
						</section>
						";
					} 
					$resultado->free();
			}
			echo "</div>";
		}
		?>
	</body>
</html>