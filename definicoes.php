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
		<?php require('header.php');
		echo "
		<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<section class='p-xl-5 p-4 bg-dark text-light'>

				<div class='row'>
					<div class='col-3 col-xl-3 col-lg-2 col-md-2 col-sm-4 pe-sm-3 pe-0'>
						<img class='rounded-circle img-fluid' src='".$url_media."fpe/".$uti['fpe'].".jpg'>
					</div>
					<div class='col-9 col-xl-9 col-lg-10 col-md-10 col-sm-8 row pe-0'>
						<div class='col-12 col-sm-8 pe-0'>
							<h2>".$uti["nut"]."</h2>
							<text class='h5'>".$uti["nco"]."</text><br>
							<text>"._('Criação da conta').": ".substr($uti["dcr"], 0, -9)."</text>
						</div>
						<div class='col-12 col-sm-4 p-sm-0 pt-2'>
							<a class='float-sm-end btn btn-vermelho' href='/pro/sair'><i class='bi bi-box-arrow-right'></i>"._('Sair')."</a>
						</div>
					</div>
				</div>

			</section>

			<section class='p-xl-5 p-4 bg-light text-dark'>

				<h2 class='mb-3'>"._('Conta')."</h2>";
				
				if ($uti['rno']==1){
					$uti_rno = 'checked';
				}

				echo "
				<div class='mb-2'>
					"._('Email')."<br>
					<div class='row'>
						<div class='col-6 col-sm-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='".$uti_mai['mai']."'>
							
						</div>
						<div class='col-auto p-0'><a href='/registo?ac=alterarMail' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>
				
				<div class='mb-5'>
					"._('Palavra-passe')."<br>
					<div class='row'>
						<div class='col-6 col-sm-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='••••••••••••'>
							
						</div>
						<div class='col-auto p-0'><a href='/entrar?ac=alterarPasse' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>

				<h2 class='mb-3'>"._('Notificações')."</h2>
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
				
				<div id='info_not' class='alert d-flex align-items-center justify-content-between border-0' role='alert'></div>
				";

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

				echo "
				
			</section>

			<section class='p-xl-5 p-4 bg-dark text-light'>

				<h2>"._("Informação")."</h2>
				<a href='/sobre' class='btn btn-primary'><i class='bi bi-info-circle'></i>"._("Sobre a drena")."</a>
				<a href='/politicas' class='btn btn-light'><i class='bi bi-file-earmark-check'></i>"._("Políticas e Termos")."</a>
				<a href='https://play.google.com/store/apps/details?id=pt.drena' class='btn btn-light'><i class='bi bi-android'></i>APP</a>
				<a href='https://github.com/drena-pt/drena/' class='btn btn-light'><i class='bi bi-github'></i>GitHub</a>

			</section>
			";
			?>

		</div>

		<script>
		function Not_denied(){
			$('#info_not').html('<span><i class="bi bi-exclamation-triangle-fill"></i> <?php echo _('As notificações estão bloqueadas, ative nas definições do browser'); ?></span>');
			$('#info_not').addClass('alert-vermelho');
			$('#info_not').removeClass('alert-dark');
		}

		function Not_default(){
			$('#info_not').html('<span><i class="bi bi-info-circle-fill"></i> <?php echo _('As notificações estão inativas neste browser'); ?></span>');
			$('#info_not').addClass('alert-dark');
		}

		function Not_granted(){
			$('#info_not').html('<span><i class="bi bi-check-circle-fill"></i> <?php echo _('As notificações estão ativas neste browser'); ?></span>');
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
			console.error('Este browser não tem suporte para notificações');
			$('#info_not').html('<span><i class="bi bi-check-circle-fill"></i> Este browser não tem suporte para notificações</span>');
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
	</body>
</html>