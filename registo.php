		<?php require('head.php') ?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
			<style>
			:root{
				--cor1: #cc00cc;
				--cor2: #990099;
			}
			</style>
			<?php
			$erros = unserialize($_COOKIE["erros"]);
			function temErro($erro){
				if ($erro){
					return "is-invalid";
				}
			}
			function nomeErro($erro){
				switch ($erro){
					case 1:
						return "Campo vazio.";break;
					case 2:
						return "As palavras-passe não podem ser diferentes.";break;
					case 3:
						return "Este mail já está em uso.";break;
					case 4:
						return "Este utilizador já está registado.";break;
					case 5:
						return "O nome de utilizador só pode conter letras e números.";break;
					case 6:
						return "Código de verificação inválido.";break;
					case 7:
						return "Não podes utilizar o mesmo email.";break;
				}
			}
			#var_dump($erros); #Mostrar erros

			if (!$_SESSION['pre_uti']){
				echo "
				<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<form action='/pro/registo.php' method='post' autocomplete='off' autocomplete='chrome-off'>
						<h1 aria-describedby='erro_campos'>Registo</h1>

						<div class='form-group'>
							<input type='text' class='form-control ".temErro($erros["nco"])."' aria-describedby='erro_nco' name='nco' placeholder='O teu nome verdadeiro'>
							<div id='erro_nco' class='invalid-feedback'>".nomeErro($erros["nco"])."</div>
						</div>

						<div class='form-group'>
							<input type='text' class='form-control ".temErro($erros["nut"])."' aria-describedby='erro_nut' name='nut' placeholder='Nome de utilizador'>
							<div id='erro_nut' class='invalid-feedback'>".nomeErro($erros["nut"])."</div>
						</div>

						<div class='form-group'>
							<input type='email' class='form-control ".temErro($erros["mai"])."' aria-describedby='erro_mai' name='mai' placeholder='Email'>
							<div id='erro_mai' class='invalid-feedback'>".nomeErro($erros["mai"])."</div>
						</div>

						
						<div class='form-group form-row'>
							<div class='col mb-3 mb-sm-auto'>
								<input type='password' class='form-control ".temErro($erros["ppa"])."' aria-describedby='erro_ppa' name='ppa' placeholder='Palavra-passe'>
								<div id='erro_ppa' class='invalid-feedback'>".nomeErro($erros["ppa"])."</div>
							</div>
							
							<div class='col-sm'>
								<input type='password' class='form-control ".temErro($erros["rppa"])."' aria-describedby='erro_rppa' name='rppa' placeholder='Repetir a palavra-passe'>
								<div id='erro_rppa' class='invalid-feedback'>".nomeErro($erros["rppa"])."</div>
							</div>
						</div>

						<div class='form-group text-center'>
							<button class='cor1 btn btn-light'>Criar conta</button>
						</div>
					</form>
				</div>
				
				<h6 class='text-center'>ou <a href='/entrar' class='btn btn-dark'>Inicia sessão</a></h6>
				";
			} else {
				$pre_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION['pre_uti']."'"));
				$mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$pre_uti['mai']."'"));
				if (!$pre_uti['id']){
					header("Location: pro/sair.php");
					exit();
				} else if ($mai['con']==1){
					header("Location: ../");
					exit();
				}

				$mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$mai['mai']."' AND con=1"));		# Procurar na base de dados pelo mail já confirmado por outro utilizador.
				
				if ($mai_confirmado OR $_GET['ac']=='alterarMail' OR !$mai){																			# Caso o mail estiver confirmado noutra conta ou o utilizador pedir para trocar o mail.
					echo "
					<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>";

						if (!$mai){
							echo "<h2>Adicionar um mail</h2>
							<text>Olá <b>".$pre_uti['nut']."</b>.<br>";
						} else {
							echo "<h2>Alterar email</h2>
							<text>Olá <b>".$pre_uti['nut']."</b>.<br>";
						}

						if ($mai_confirmado){
							echo "O mail <b>".$mai['mai']."</b> já fó registado e está a ser utilizado por outra conta.<br>Por favor utiliza outro email.</text>";
						} else if (!$mai){
							echo "Atualmente não tens nenhum email para recuperação e contacto da tua conta. Adiciona um email abaixo.";
						} else {
							echo "O mail teu mail atual é <b>".$mai['mai']."</b>, regista o novo email desejado abaixo.</text>";
						}
						
						echo "
						<form action='pro/registo.mai?ac=registarMail' method='post'>
							<div class='form-row align-items-center my-3'>
								<div class='col-8'>
									<input type='email' class='form-control ".temErro($erros["mai"])."' aria-describedby='erro_mai' name='mai' placeholder='Novo endereço de mail'>
									<div id='erro_mai' class='invalid-feedback'>".nomeErro($erros["mai"])."</div>
								</div>

								<div class='col-3 align-self-start'>
									<button type='submit' class='cor1 btn btn-light'>";
									if (!$mai){
										echo "Adicionar";
									} else {
										echo "Alterar";
									}
									echo "
									</button>
								</div>
							</div>
							";
							if ($mai){
								echo "<a href='/registo' class='cor2 btn btn-light'>Confirmar email</a>";
							}
							echo "
						</form>
					</div>
					";
				} else {
					echo "
					<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
						<h2>Confirmar ativação da conta</h2>
						<text>Olá <b>".$pre_uti['nut']."</b>.<br>Enviámos um código de verificação para <b>".$mai['mai']."</b><br>Pode demorar algum tempo até chegar o mail, verifica na caixa de spam.</text>

						<form action='pro/registo.mai?ac=confirmar' method='post'>
							<div class='form-row align-items-center my-3'>
								<div class='col-8'>
									<input type='text' maxlength='8' class='form-control ".temErro($erros["cod"])."' aria-describedby='erro_cod' name='cod' placeholder='Código de verificação'>
									<div id='erro_cod' class='invalid-feedback'>".nomeErro($erros["cod"])."</div>
								</div>

								<div class='col-3 align-self-start'>
									<button type='submit' class='cor1 btn btn-light'>Verificar</button>
								</div>
							</div>
						</form>
						";
						$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($mai['ure']));

						if ($mai['ree']<=2 AND $tempoUltimoEmail>=300){
							echo "<a href='pro/registo.mai?ac=reenviarMail' class='cor2 btn btn-light'>Reenviar mail</a>";
						} else if ($mai['ree']<=2){
							echo "<span data-toggle='tooltip' data-placement='bottom' title='Espera 5 minutos antes de reenviar um mail.'><a class='disabled cor2 btn btn-light'>Reenviar mail</a></span>";
						} else {
							echo "<span data-toggle='tooltip' data-placement='bottom' title='Excedeste o limite de 3 mails diários.'><a class='disabled cor2 btn btn-light'>Reenviar mail</a></span>";
						}
						echo "
						<a href='/registo?ac=alterarMail' class='cor2 btn btn-light'>Alterar email</a>
					</div>
					";
				}
			}
			?>
		</div>
	</body>
</html>