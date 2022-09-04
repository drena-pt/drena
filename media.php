		<?php
		require('head.php');
		$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));

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
			if ($med['tit']){$med_tit = $med['tit'];} else {$med_tit = $med['nom'];}															# Definir título
			$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));									# Utilizador dono
			$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$_GET["id"]."' AND uti='".$uti['id']."';"));	# Informações do gosto do utilizador logado
		
			echo "
			<!-- Tags de motor de pequisa -->
			<meta property='og:title' content='".$med_tit."'/>
			<meta property='og:description' content='".$med_uti['nut'].", ".sprintf(_('há %s'),tempoPassado(strtotime($med['den']))).", ".$med['gos']." "._('gostos')."'/>
			<meta property='og:url' content='".$url_site."media?id=".$med['id']."'/>
			<meta property='og:image' content='".$url_media."thumb/".$med['thu'].".jpg'/>
			
			";

			#Se for um vídeo
			if ($med['tip']==1){
				echo "<meta property='og:type' content='video' />";
				if ($med['est']==3){ #Estado 3 (comprimido).
					echo "<meta property='og:video' content='".$url_media."comp/".$med["id"].".mp4' />";					
				} else if ($med['est']==5){ #Estado 5 (convertido).
					echo "<meta property='og:video' content='".$url_media."conv/".$med["id"].".mp4' />";					
				} else { #Todos os outros estados.
					echo "<meta property='og:video' content='".$url_media."ori/".$med["id"].".".end(explode(".", $med['nom']))."' />";
				}
			}
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
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
									<iframe style='position:absolute;top:0;left:0;width:100%;height:100%;' src='/embed?id=".$med['id']."&titulo=0'></iframe>
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
												<a href='/pro/med.php?id=".$med['id']."&ac=comprimir' role='button' class='btn btn-light text-dark'>"._('Comprimir vídeo')." <i class='bi bi-gear'></i></a>
											</div>
										</div>
									</div>";
								} else if ($med['est']=='2'){
									echo "<div class='p-xl-5 p-4 bg-primary'><text class='h5 my-auto me-auto'><i class='bi bi-info-circle'></i> "._('O vídeo está a ser processado...')."</text></div>";
								}
							}

							echo "<div class='p-xl-5 p-4'>";

							break;
						case 2: # Áudio
							$t_eliminar = _('Eliminar áudio');
							$t_cor = 'rosa';
							echo "<iframe height='180px' class='w-100' src='/embed?id=".$med['id']."&titulo=0'></iframe>
							<div class='p-xl-5 p-4'>";
							break;
						case 3: # Imagem
							$t_eliminar = _('Eliminar imagem');
							$t_cor = 'ciano';
							echo "
							<iframe style='min-height:50vh;' class='w-100' src='/embed?id=".$med['id']."'></iframe>
							<div class='p-xl-5 p-4'>";
							break;
					}
					echo "
						<div class='row mb-3'>
							<div class='col-12 col-md d-flex'>
								<text class='h5 my-auto'>".$med_tit."</text>
							</div>

							<div class='col-md my-md-0 my-2 d-flex flex-md-row-reverse flex-row'>
							";

						if ($uti['id']==$med_uti['id']){ # Botões de gestão de média, para o utilizador dono
							if ($med['tip']=='1' OR $med['tip']=='2'){ # Caso seja um vídeo ou um áudio, para mudar thumbnail
								echo "
								<style>
								#thumb_a_carregar {
									text-align: center;
									padding: 0 20px;
									max-height: 24px;
								}
								.box {
									position: relative;
									width: 16px;
									height: 16px;
									margin: 4px;
									display: inline-block;
									background-color: #000;
								}
								</style>
								<span><label for='input_thu' role='button' class='btn btn-light me-1 my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title=\""._('Alterar miniatura')."\">
									<span id='thumb_carregar'><i class='bi bi-file-earmark-image'></i></span>
									<div id='thumb_a_carregar' style='display:none;' data-placement='bottom' data-toggle='tooltip' title=\""._('A carregar...')."\">
										<div class='box'></div>
									</div>
								</label></span>
								<form hidden enctype='multipart/form-data' action='#' method='post'>
									<input type='file' id='input_thu' name='thu' accept='image/*'/>
									<input type='submit'/>
								</form>
								<script>
								$('#input_thu').change(function(objEvent) {
									var objFormData = new FormData();
									var objFile = $(this)[0].files[0];
									objFormData.append('thu', objFile);
									$('#thumb_a_carregar').show();
									$('#thumb_carregar').hide();
									$.ajax({
										url: '/pro/med_thu.php?med=".$med['id']."',
										type: 'POST',
										contentType: false,
										data: objFormData,
										processData: false,
										success: function(output){
											if (output){
												alert(output);
											}
											location.reload();
										}
									});
								});
								anime({
									targets: '.box',
									keyframes: [
										{translateX: 16, rotate: '90deg'},
										{translateX: 0, rotate: '0deg'},
										{translateX: -16, rotate: '-90deg'},
										{translateX: 0, rotate: '0deg'}
									],
									duration: '3500',
									loop: true,
									easing: 'easeInOutBack',
									direction: 'normal'
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
													<text class='h5'>".$med_tit."</text>
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
																<a href='/pro/med_alb.php?ac=adicionar&redirect=1&alb=".$campo['id']."&med=".$med['id']."' class='list-group-item bg-transparent px-0'>
																	<section class='p-4 bg-light bg-cover text-dark rounded-xl shadow d-flex justify-content-between align-items-center' style='background-image: linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.8)), url(\"".$url_media."thumb/".$campo['thu'].".jpg\");'>
																		<h5 class='m-0'>".$alb_tit."</h5>
																		<span class='badge rounded-pill bg-dark text-light'>".$alb_num_med."</span>
																	</section>
																</a>";
															}

														}
														$resultado->free();
														echo "
														<a href='/pro/med_alb.php?ac=criar&med=".$med['id']."' class='list-group-item bg-transparent px-0'>
															<section class='p-2 px-4 bg-light text-dark rounded-xl shadow d-flex justify-content-between align-items-center'>
																<h5 class='m-0'>"._('Criar álbum')."</h5><i class='bi bi-plus-circle'></i>
															</section>
														</a>
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
								</div>";
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
								<a href='pro/med.php?ac=privar&id=".$med['id']."' role='button' class='btn btn-".$bg_privado." me-1 my-auto' data-toggle='tooltip' data-placement='bottom' data-original-title=\"".$t_privado."\">
									<i class='bi bi-".$i_privado."'></i>
								</a>
							</span>

							<!-- Modal Alterar título -->
							<div class='modal fade' id='modal_alerar_tit' tabindex='-1' role='dialog' aria-labelledby='modal_alerar_tit_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<form action='pro/med.php?ac=titulo&id=".$_GET['id']."' method='post'>
											<div class='modal-header'>
												<h2 class='modal-title' id='modal_alerar_tit_label'>"._('Alterar título')."<br></h2><br>
											</div>
											<div class='modal-body'>
												<input type='text' class='form-control' name='tit' placeholder='"._('Título')."' value='".$med_tit."'>
											</div>
											<div class='modal-footer text-end'>
												<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
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
											<text><span class='h5'>".$med_tit."</span><br>"._('Esta ação é irreversível!')."</text>
										</div>
										<div class='modal-footer text-end'>
											<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
											<a href='pro/med.php?ac=eliminar&id=".$_GET['id']."' role='button' class='btn btn-vermelho text-light'>"._('Eliminar')."</a>
										</div>
									</div>
								</div>
							</div>
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
											<text class='h5'>".$med_tit."</text><br><br>";
											#Registos da ultima moderação feita pelo utilizador com sessão iniciada:
											#Nivel 0 (Reverter ação)
											$med_mod_uti0 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='0';"));
											#Nivel 1 (Inapropriado)
											$med_mod_uti1 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='1';"));
											#Nivel 2 (Inaceitavel)
											$med_mod_uti2 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='2';"));

											if ($med['nmo']!=0 AND $med['nmo']!=2){
												#Registo do ultimo voto
												$med_mod = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND niv>'0' ORDER BY dre DESC;"));
												#Registo do moderador do ultimo voto
												$med_mod_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med_mod['uti']."'"));

												echo "
												<div class='row my-4'>
													<div class='col-auto pe-0 text-center'>
														<a href='/perfil?uti=".$med_mod_uti['nut']."'><img src='fpe/".base64_encode($med_mod_uti["fot"])."' class='rounded-circle' width='40'></a>
													</div>
													<div class='col d-flex'>
														<span class='justify-content-center align-self-center'>
															"._('Pedido pelo moderador')." ".$med_mod_uti['nut'].":<br>";
															#Define a ação a tomar em texto
															if ($med['nmo']==1){
																echo "Definir como sensível";
															} else if ($med['nmo']==3){
																echo "Definir como contéudo inaceitável";
															} else if ($med['nmo']==4){
																echo "Eliminar contéudo inaceitável";
															}
															echo "
														</span>
													</div>
												</div>
												";

												#Se o moderador com sessão iniciada nunca tiver escolhido as diferentes ações;
												if (!$med_mod_uti0){
													echo "<a href='/pro/med_mod.php?med=".$med['id']."&ac=0' role='button' class='me-1 btn btn-ciano text-light'>"._('Discordo')."</a>";
												} else {
													echo "<button disabled class='me-1 btn btn-ciano text-light'>"._('Discordo')."</button>";
												}
												if ($med['nmo']==1){
													if ($med_mod_uti1){
														echo "<button disabled class='btn btn-ciano text-light'>"._('Concordo')."</button>";
													} else {
														echo "<a href='/pro/med_mod.php?med=".$med['id']."&ac=1' role='button' class='btn btn-ciano text-light'>"._('Concordo')."</a>";
													}
												} else if ($med['nmo']==3 OR $med['nmo']==4){
													if ($med_mod_uti2){
														echo "<button disabled class='btn btn-ciano text-light'>"._('Concordo')."</button>";
													} else {
														echo "<a href='/pro/med_mod.php?med=".$med['id']."&ac=2' role='button' class='btn btn-ciano text-light'>";
														if ($med['nmo']==3){
															echo _('Concordo');
														} else if ($med['nmo']==4){
															echo _('Eliminar');
														}
														echo "</a>";
													}
												}

											} else if ($med['nmo']!=2){
												if ($med_mod_uti1){
													echo "<button disabled class='btn btn-ciano text-light'>"._('Definir como contéudo sensível')." <i class='bi bi-eye-slash'></i></button>";
												} else {
													echo "<a href='/pro/med_mod.php?med=".$med['id']."&ac=1' role='button' class='btn btn-ciano text-light'>"._('Definir como contéudo sensível')." <i class='bi bi-eye-slash'></i></a>";
												}
											}
											if ($med['nmo']<3){
												if ($med_mod_uti2){
													echo "<button disabled class='btn btn-vermelho text-light'>"._('Reportar como contéudo inaceitável')." <i class='bi bi-x-octagon'></i></button>";
												} else {
													echo "<a href='/pro/med_mod.php?med=".$med['id']."&ac=2' role='button' class='btn btn-vermelho text-light'>"._('Reportar como contéudo inaceitável')." <i class='bi bi-x-octagon'></i></a>";
												}
											}
											echo "
										</div>
										<div class='modal-footer text-end'>
											<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
										</div>
									</div>
								</div>
							</div>
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
									<a href='/perfil?uti=".$med_uti['nut']."'><img src='fpe/".base64_encode($med_uti["fot"])."' class='rounded-circle' width='40'></a>
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
							<a href='/perfil?uti=".$uti['nut']."'><img src='fpe/".base64_encode($uti["fot"])."' class='rounded-circle' width='40'></a>
						</div>
						<div class='col d-flex'>
							<span class='justify-content-center align-self-center'>"._('Comentado por')." ".$uti['nut']."</span>
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
					<button id='botao_caixa_comentario' href='/registo' class='btn btn-primary'>"._('Adicionar um comentário')."</button>
				</div>
				
				<div style='display:none;' id='caixa_comentario' class='my-4 p-5 bg-primary bg-gradient rounded-xl shadow text-light'>
					<form id='form_comentario'>
						<h2>"._('Adicionar um comentário')."</h2>
						<input id='input_com' type='text' class='form-control' autocomplete='off' name='input_com' placeholder='"._('Comentário')."'>
						<div class='text-end'>
							<button id='botao_fechar_caixa_comentario' type='button' class='btn btn-dark'>"._('Fechar')."</button>
							<button type='submit' class='btn btn-light text-primary'>"._('Comentar')."</button>
						</div>
					</form>
				</div>

				<script>
				function gosto(){
					$.ajax({
						url: 'pro/med_gos.php?med=".$med['id']."',
						success: function(result) {
							var gostos = +$('#texto_gostos').text();
							if (result==='false'){
								$('#botao_gosto').attr('hidden', true);
								$('#botao_naogosto').removeAttr('hidden');
								$('#texto_gostos').text(gostos-1);
							} else {
								$('#botao_gosto').removeAttr('hidden');
								$('#botao_naogosto').attr('hidden', true);
								$('#texto_gostos').text(gostos+1);
							}
						},
						error: function(){
							alert('Ocorreu um erro.');
						}
					});
				}

				function eliminar_com(com_id){
					setTimeout(function(){
						$.ajax({
							url: 'api/med_com.php?med=".$med['id']."&ac=eliminar&uti=".$uti['nut']."&cod=".$uti_mai['cod']."&id='+com_id,
							success: function(result) {
								if (result['err']){
									alert(result['err']);
								} else {
									$('#com_'+com_id).remove();
								}
							},
							error: function(){
								alert('Ocorreu um erro.');
							}
						});
					}, 1000);
				}

				function fechar_comentario() {
					$('#caixa_botao_comentario').show();
					$('#caixa_comentario').hide();
				}

				$('#botao_caixa_comentario').on('click', function() {
					$('#caixa_comentario').show();
					$('#caixa_botao_comentario').hide();
				});
				$('#botao_fechar_caixa_comentario').on('click', fechar_comentario);
				
				$('#form_comentario').on('submit', function(e) {
					e.preventDefault();
					var com = $('#input_com').val();
					$.ajax({
						url: 'api/med_com.php?med=".$med['id']."&ac=criar&uti=".$uti['nut']."&cod=".$uti_mai['cod']."&com='+com,
						success: function(result) {
							fechar_comentario();
							$('#input_com').val('');
							var com_id = result['id'];
							$('#caixa_comentario').after(\"".preg_replace( "/\r|\n/", "", $caixa_cometario)."\");
						},
						error: function(){
							console.log('Ocorreu um erro.');
						}
					});
				});
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
									<a href='/perfil?uti=".$com_uti['nut']."'><img src='fpe/".base64_encode($com_uti["fot"])."' class='rounded-circle' width='40'></a>
								</div>
								<div class='col d-flex'>
									<span class='justify-content-center align-self-center'>"._('Comentado por')." ".$com_uti['nut']."</span>
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
		</div>
	</body>
</html>