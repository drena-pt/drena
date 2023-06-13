	<?php 
	require('head.php');
	if (!$uti){
		header("Location: /entrar.php"); exit;
	}
	?>
		<script src='/js/api.min.js'></script>
		<script src='/js/notificacoes.js'></script>
	</head>
	<body>
		<?php require('header.php'); ?>
		<div class="shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3">
			<div class="p-xl-5 p-4 bg-dark text-light">

				<div class="row">
					<div class='col-3 col-xl-3 col-lg-2 col-md-2 col-sm-4 pe-sm-3 pe-0'>
						<img class="rounded-circle img-fluid" src='<?php echo $url_media."fpe/".$uti['fpe'].".jpg"; ?>'>
					</div>
					<div class="col-9 col-xl-9 col-lg-10 col-md-10 col-sm-8 row pe-0">
						<div class='col-12 col-sm-8 pe-0'>
							<h2><?php echo $uti["nut"];?></h2>
							<text class='h5'><?php echo $uti["nco"];?></text><br>
							<text><?php echo _('Criação da conta').": ".substr($uti["dcr"], 0, -9);?></text>
						</div>
						<div class='col-12 col-sm-4 p-sm-0 pt-2'>
							<a class="float-sm-end btn btn-light" href='/pro/sair'><?php echo _('Sair');?> <i class="bi bi-box-arrow-right"></i></a>
						</div>
					</div>
				</div>

			</div>
			<div class="p-xl-5 p-4 bg-light text-dark">
				<h2 class='pb-3'><?php echo _('Configurações'); ?></h2>

				
				<?php
				/*echo "Idioma
				<div class='row'>
					<div class='col-4 mr-auto'>
						<select class='form-control text-dark border-dark' style='cursor:pointer;'>
							<option>Deutsch</option>
							<option>English</option>
							<option>Français</option>
							<option>Italiano</option>
							<option selected>Português</option>
						</select>
					</div>
					<div class='col-auto p-0'><button class='btn btn-dark'>"._('Guardar')."</button></div>
				</div>";*/

				if ($uti['rno']==1){
					$uti_rno = 'checked';
				}

				echo "
				<div class='mb-4'>
					"._('Email')."<br>
					<div class='row'>
						<div class='col-6 col-sm-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='".$uti_mai['mai']."'>
							
						</div>
						<div class='col-auto p-0'><a href='/registo?ac=alterarMail' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>
				
				
				<div class='mb-4'>
					"._('Palavra-passe')."<br>
					<div class='row'>
						<div class='col-6 col-sm-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='••••••••••••'>
							
						</div>
						<div class='col-auto p-0'><a href='/entrar?ac=alterarPasse' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>

				<hr>

				<div class='my-4'>
					<div class='form-check form-switch'>
						<input type='checkbox' role='switch' class='form-check-input' ".$uti_rno." id='switch_not_uti'>
						<label class='form-check-label' for='switch_not_uti'>
						"._('Receber notificações')."
						</label>
					</div>

					<div class='form-check form-switch'>
                        <input type='checkbox' role='switch' class='form-check-input' id='switch_not_sub'>
                        <label class='form-check-label' for='switch_not_sub'>
						"._('Notificações subscritas neste dispositivo')."
						</label>
                    </div>
				</div>
				
				<div id='info_not' class='alert d-flex align-items-center justify-content-between' role='alert'></div>
				";
				?>
				<script>
				function Not_denied(){
					$('#info_not').html('<span><i class="bi bi-exclamation-triangle-fill"></i> <?php echo _('As notificações estão bloqueadas, ative nas definições do Browser'); ?></span>');
					$('#info_not').addClass('alert-vermelho');
					$('#info_not').removeClass('alert-dark');
				}

				function Not_default(){
					$('#info_not').html('<span><i class="bi bi-info-circle-fill"></i> <?php echo _('As notificações estão inativas neste Browser'); ?></span>');
					$('#info_not').addClass('alert-dark');
				}

				function Not_granted(){
					$('#info_not').html('<span><i class="bi bi-check-circle-fill"></i> <?php echo _('As notificações estão ativas neste Browser'); ?></span>');
					$('#info_not').addClass('alert-primary');
					$('#info_not').removeClass('alert-dark');
					//Obtem se está subscrito ou não
					not_sub("ob").then((res) => {
						console.debug("Está subscrito? "+res);
						if (res=='true'){
							$('#switch_not_sub').prop('checked', true);
						}
					});
				}
				
				function Not_unsuported(){
					console.error('Este Browser não tem suporte para notificações');
					$('#info_not').html('<span><i class="bi bi-check-circle-fill"></i> Este Browser não tem suporte para notificações</span>');
				}

				function checar(){
					console.debug("Notification.premission: "+Notification.permission);
					if (!('Notification' in window)) { Not_unsuported();
					} else if (Notification.permission === 'default') { Not_default();
					} else if (Notification.permission === 'granted') { Not_granted();
					} else { Not_denied(); }
				}
				checar();

				$('#switch_not_uti').change(function() {
					result = api('not',{'ac':'receber'});
					console.debug('Receber notificações: '+result['est']);
				});

				$('#switch_not_sub').change(function() {
					not_sub("ac").then((res) => {
						if (res!='true'){
							$(this).prop('checked', false);
						}
						checar();
					});
				});
				</script>
				
			</div>
		</div>
	</body>
</html>