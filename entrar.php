		<?php require('head.php') ?>
		<script src='/js/api.min.js'></script>
	</head>
	<body>
		<?php require('header.php'); ?>
		<?php
		if ($_GET['ac']=='recuperar') { # Se a ação for recuperar a conta
			if ($_COOKIE['mailEnviado']){
				echo "
				<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<h2>"._('Email enviado')."</h2>
					<text>"._('Foi enviado um email com o link para recuperação da conta associada ao email.')."<br>"._('Pode demorar algum tempo até o email chegar, verifica na caixa de spam.')."</text>
				</div>
				";
			} else {
				echo "
				<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<h2>"._('Recuperar conta')."</h2>
					<text>"._('Se não te lembras da tua palavra-passe, insere o teu email associado à conta.')."<br><br></text>

					<form id='form_recuperar'>
						<div class='form-group'>
							<input id='mai' placeholder='"._('Endereço de email')."' type='email' class='form-control'>
							<div id='erro_mai' class='invalid-feedback'></div>
						</div>

						<div class='form-group text-center'>
							<button class='text-primary btn btn-light'>"._('Enviar email de recuperação')."</button>
						</div>
					</form>
				</div>

				<script>
				$('#form_recuperar').on('submit', function(e) {
					e.preventDefault();
					var mai = $('#mai').val();
	
					r = api('ppa',{'ac':'recuperar','mai':mai});
					if (r.est=='sucesso'){
						window.location.reload();
					} else {
						avisos(r.avi);
					}
				});
				</script>
				";
			}
		} else if ($_GET['ac']=='alterarPasse') { #Se a ação for alterar a palavra-passe

				#Se o utilizador não estiver com sessão iniciada
				if (!$uti){
					#Obtem informações do utilizador atravez do GET
					$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));
					$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti["mai"]."' AND con=1"));

					#Verifica se o código obtido pelo GET coincide com o mail do utilizador
					if (!$_GET['cod'] OR $uti_mai['cod']!=$_GET['cod']){
						header("Location: /entrar");
						exit;
					}
				}
				
				$texto_alterada = "
				<h2>"._('Palavra-passe alterada')."</h2>
				<text>"._('A palavra-passe foi alterada com sucesso, já podes iniciar sessão!')."<br><br></text>
				<div class='text-center'>
					<a href='/entrar' class='text-primary btn btn-light'>"._('Iniciar sessão')."</a>
				</div>
				";

				echo "
				<div id='div_alterar' class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<h2>"._('Alterar a palavra-passe')."</h2>
					<text>".sprintf(_('Olá %s'),'<b>'.$uti['nut'].'</b>')."<br>"._('Insere a nova palavra-passe.')."<br><br></text>
	
					<form id='form_passe'>
						<div class='form-group form-row'>
							<div class='col mb-3 mb-sm-auto'>
								<input id='ppa' placeholder='"._('Nova palavra-passe')."' aria-describedby='erro_ppa' type='password' class='form-control'>
								<div id='erro_ppa' class='invalid-feedback'></div>
							</div>

							<div class='col-sm'>
								<input id='rppa' placeholder='"._('Repetir a palavra-passe')."' aria-describedby='erro_rppa' type='password' class='form-control'>
								<div id='erro_rppa' class='invalid-feedback'></div>
							</div>
						</div>

						<div class='form-group text-center'>
							<button type='submit' class='text-primary btn btn-light'>"._('Alterar a palavra-passe')."</button>
						</div>
					</form>
				</div>

				<script>
				$('#form_passe').on('submit', function(e) {
					e.preventDefault();
					var ppa = $('#ppa').val();
					var rppa = $('#rppa').val();
	
					r = api('ppa',{'ac':'alterar','ppa':ppa,'rppa':rppa,'uti':'".$_GET["uti"]."','cod':'".$_GET["cod"]."'});
					if (r.est=='sucesso'){
							$('#div_alterar').html(\"".preg_replace("/\r|\n/", "", $texto_alterada)."\");
					} else {
						avisos(r.avi);
					}
				});
				</script>
				";

		} else { #Se não houver ação apresentar ecrã: LOGIN PADRÃO
			echo "
			<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
				<form id='form_entrar'>
					<h1>"._('Entrar')."</h1>

					<div class='form-group'>
						<input id='nut' type='text' placeholder='"._('Utilizador')."' class='form-control' aria-describedby='erro_nut'>
						<div id='erro_nut' class='invalid-feedback'></div>
					</div>

					<div class='form-group'>
						<input id='ppa' type='password' placeholder='"._('Palavra-passe')."' class='form-control' aria-describedby='erro_ppa'>
						<div id='erro_ppa' class='invalid-feedback'></div>
					</div>

					<div class='form-group text-center'>
						<input type='submit' class='text-primary btn btn-light' value='"._('Iniciar sessão')."'>
					</div>
				</form>
			</div>
			
			<div class='text-center'>
				<a id='btn_recuperar' href='?ac=recuperar' class='d-none btn btn-light text-primary'>"._('Recuperar conta')."</a>
				<a href='/registo' class='btn btn-primary'>"._('Criar uma conta')."</a>
			</div>

			
			";
		}
		?>
		<script>
		function textoErro(erro){
			switch (erro){
				case 1:
					return ('Campo vazio.');break;
				case 2:
					return ('Utilizador inválido.');break;
				case 3:
					return ('A palavra-passe está errada.');break;
				case 4:
					return ('Email inválido.');break;
				case 5:
					return ('Excedeste o limite de emails.');break;
				case 6:
					return ('As palavras-passe não podem ser diferentes.');break;
			}
		}

		$('#form_entrar').on('submit', function(e) {
			e.preventDefault();
			var nut = $('#nut').val();
			var ppa = $('#ppa').val();
			r = api('entrar',{'nut':nut,'ppa':ppa});
			if (r.est=='sucesso'){
				window.location.href = '/';
			} else if (r.est=='registo'){
				window.location.href = '/registo';
			} else {
				avisos(r.avi);
				if (r.avi.ppa){
					$('#btn_recuperar').removeClass('d-none');
				}
			}
		});

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
		</script>
	</body>
</html>