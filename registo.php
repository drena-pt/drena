		<?php require('head.php') ?>
		<script src='/js/api.min.js'></script>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<?php
		#Se existir uma sessão aberta
		if ($_SESSION['pre_uti'] OR $uti){

			if ($_SESSION['pre_uti']){
				$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION['pre_uti']."'"));
				$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
				/* 
				if (!$uti['id']){
					#A implementar quando fizer algo para apagar contas não verificadas durante varios tempos
					header("Location: /pro/sair.php"); exit();
				}
				*/
			}

			#Procurar na base de dados pelo email já confirmado
			$uti_mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$uti_mai['mai']."' AND con=1"));

			#Se o email estiver confirmado, ou o utilizador pedir para trocar ou não tiver nenhum
			if ($uti_mai_confirmado OR $_GET['ac']=='alterarMail' OR !$uti_mai){
				echo "
				<div class='bg-ciano bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>";

					if (!$uti_mai){
						echo "<h2>"._("Adicionar um email")."</h2>";
					} else {
						echo "<h2>"._("Alterar email")."</h2>";
					}

					echo "<text>". sprintf(_("Olá %s"),'<b>'.$uti['nut'].'</b>') ."<br>";

					if ($uti_mai_confirmado AND $_SESSION['pre_uti']){
						echo sprintf(_("O email %s já foi registado e está a ser utilizado por outra conta. Por favor utiliza outro email."),'<b>'.$uti_mai['mai'].'</b>') ."</text>";
					} else if (!$uti_mai){
						echo _("Atualmente não tens nenhum email para recuperação e contacto da tua conta. Adiciona um email abaixo.")."</text>";
					} else {
						echo sprintf(_("O teu email atual é %s, regista o novo email abaixo."),'<b>'.$uti_mai['mai'].'</b>')."</text>";
					}
					
					echo "
					<form id='form_email'>
						<div class='form-row align-items-center my-3'>
							<div class='col-8'>
								<input id='mai' aria-describedby='erro_mai' type='email' placeholder='"._("Novo endereço de email")."' class='form-control'>
								<div id='erro_mai' class='invalid-feedback'></div>
							</div>

							<div class='col-3 align-self-start'>
								<button type='submit' class='btn btn-light text-ciano'>";
								if (!$uti_mai){
									echo _("Adicionar");
								} else {
									echo _("Alterar");
								}
								echo "
								</button>
							</div>
						</div>
						";
						if ($uti_mai AND !$uti_mai_confirmado){
							echo "<a href='/registo' class='btn btn-light text-ciano'>"._("Confirmar email")."</a>";
						}
						echo "
					</form>
				</div>
				<script>
				$('#form_email').on('submit', function(e) {
					e.preventDefault();
					var mai = $('#mai').val();
	
					r = api('mai',{'ac':'registar','mai':mai});
					if (r.est=='sucesso'){
						window.location.href='/registo';
					} else {
						avisos(r.avi);
					}
				});
				</script>";

			} else {
				echo "
				<div class='bg-ciano bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<h2>"._("Confirmar ativação da conta")."</h2>
					<text>". sprintf(_("Olá %s"),'<b>'.$uti['nut'].'</b>') ."<br>". sprintf(_("Enviámos um código de verificação para %s"),'<b>'.$uti_mai['mai'].'</b>') ."<br>"._("Pode demorar algum tempo até o email chegar, verifica na caixa de spam.")."</text>

					<form id='form_codigo'>
						<div class='form-row align-items-center my-3'>
							<div class='col-8'>
								<input id='cod' aria-describedby='erro_cod' placeholder='"._("Código de verificação")."' type='text' maxlength='8' class='form-control'>
								<div id='erro_cod' class='invalid-feedback'></div>
							</div>

							<div class='col-3 align-self-start'>
								<button type='submit' class='btn btn-light text-ciano'>"._("Verificar")."</button>
							</div>
						</div>
					</form>
					";
					$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($uti_mai['ure']));

					if ($uti_mai['ree']<=2 AND $tempoUltimoEmail>=300){
						echo "<button id='btn_reenviar' class='btn btn-light text-ciano'>"._("Reenviar email")."</button>";
					} else if ($uti_mai['ree']<=2){
						echo "<span data-toggle='tooltip' data-placement='bottom' title='"._("Espera 5 minutos antes de reenviar um email").".'><a class='disabled btn btn-light text-ciano'>"._("Reenviar email")."</a></span>";
					} else {
						echo "<span data-toggle='tooltip' data-placement='bottom' title='"._("Excedeste o limite de emails.")."'><a class='disabled btn btn-light text-ciano'>"._("Reenviar email")."</a></span>";
					}
					echo "
					<a href='/registo?ac=alterarMail' class='btn btn-light text-ciano'>"._("Alterar email")."</a>
				</div>

				<script>
				$('#form_codigo').on('submit', function(e) {
					e.preventDefault();
					var cod = $('#cod').val();
	
					r = api('mai',{'ac':'confirmar','cod':cod});
					if (r.est=='sucesso'){
						window.location.href='/u/".$uti['nut']."';
					} else {
						avisos(r.avi);
					}
				});

				$('#btn_reenviar').click(function() {
					r = api('mai',{'ac':'reenviar'});
					if (r.est=='sucesso'){
						window.location.href='/registo';
					} else {
						console.debug(r);
					}
				});
				</script>
				";
			}
		} else {
			echo "
			<div class='bg-ciano bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
				<form id='form_registo'>
					<h1>"._("Registo")."</h1>

					<div class='form-group'>
						<input id='nco' aria-describedby='erro_nco' placeholder='"._("Primeiro e último nome")."' type='text' class='form-control'>
						<div id='erro_nco' class='invalid-feedback'></div>
					</div>

					<div class='form-group'>
						<input id='nut' aria-describedby='erro_nut' placeholder='"._('Nome de utilizador')."' type='text' class='form-control' autocomplete='off'>
						<div id='erro_nut' class='invalid-feedback'></div>
					</div>
					
					<div class='form-group form-row'>
						<div class='col mb-3 mb-sm-auto'>
							<input id='ppa' aria-describedby='erro_ppa' placeholder='"._("Palavra-passe")."' type='password' class='form-control'>
							<div id='erro_ppa' class='invalid-feedback'></div>
						</div>
						
						<div class='col-sm'>
							<input id='rppa' aria-describedby='erro_rppa' placeholder='"._("Repetir a palavra-passe")."' type='password' class='form-control'>
							<div id='erro_rppa' class='invalid-feedback'></div>
						</div>
					</div>

					<div class='form-check mb-3'>
						<input type='checkbox' class='form-check-input' id='check_politicas' required>
						<label class='form-check-label' for='check_politicas'>"._("Concordo com as")." <a class='text-light' href='politicas'>"._("Políticas e Termos")."</a></label>
					</div>

					<div class='form-group text-center'>
						<input type='submit' class='text-ciano btn btn-light' value='"._('Criar uma conta')."'>
					</div>
				</form>
			</div>
			
			<div class='text-center'>
				<a href='/entrar' class='btn btn-ciano text-light'>"._("Iniciar sessão")."</a>
			</div>

			<script>
			$('#form_registo').on('submit', function(e) {
				e.preventDefault();
				var nco = $('#nco').val();
				var nut = $('#nut').val();
				var ppa = $('#ppa').val();
				var rppa = $('#rppa').val();
	
				r = api('registo',{'nco':nco,'nut':nut,'ppa':ppa,'rppa':rppa});
				if (r.est=='sucesso'){
					window.location.reload();
				} else {
					avisos(r.avi);
				}
			});
			</script>
			";
		}

		echo "
		<script>
		function textoErro(erro){
			switch (erro){
				case 1:
					return '"._("Campo vazio.")."';break;
				case 2:
					return '"._("As palavras-passe não podem ser diferentes.")."';break;
				case 3:
					return '"._("Este email já está em uso.")."';break;
				case 4:
					return '"._("Este utilizador já está registado.")."';break;
				case 5:
					return '"._("Não podes usar caracteres especiais.")."';break;
				case 6:
					return '"._("Código de verificação inválido.")."';break;
				case 7:
					return '"._("Não podes utilizar o mesmo email.")."';break;
			}
		}

		function avisos(avi){
			Object.keys(avi).forEach(function(i){
				if (avi[i]){
					$('#erro_'+i).html(textoErro(avi[i]));
					$('#'+i).addClass('is-invalid');
				} else {
					$('#'+i).removeClass('is-invalid');
				}
			});
		}
		</script>";
		?>
	</body>
</html>