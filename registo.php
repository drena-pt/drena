		<?php require('head.php') ?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
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
					return _("Campo vazio.");break;
				case 2:
					return _("As palavras-passe não podem ser diferentes.");break;
				case 3:
					return _("Este email já está em uso.");break;
				case 4:
					return _("Este utilizador já está registado.");break;
				case 5:
					return _("Não podes usar caracteres especiais.");break;
				case 6:
					return _("Código de verificação inválido.");break;
				case 7:
					return _("Não podes utilizar o mesmo email.");break;
			}
		}
		#var_dump($erros); #Mostrar erros

		#Se existir uma sessão de um utilizador com mail por confirmar
		if ($_SESSION['pre_uti'] OR $uti){

			if ($_SESSION['pre_uti']){
				$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION['pre_uti']."'"));
				$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
				if (!$uti['id']){
					header("Location: pro/sair.php");
					exit();
				} else if ($uti_mai['con']==1){
					header("Location: ../");
					exit();
				}
			}

			$uti_mai_confirmado = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE mai='".$uti_mai['mai']."' AND con=1")); # Procurar na base de dados pelo mail já confirmado por outro utilizador.
			
			if ($uti_mai_confirmado OR $_GET['ac']=='alterarMail' OR !$uti_mai){ # Se o mail estiver confirmado noutra conta ou o utilizador pedir para trocar o mail.
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
					<form action='pro/registo.mai?ac=registarMail' method='post'>
						<div class='form-row align-items-center my-3'>
							<div class='col-8'>
								<input type='email' class='form-control ".temErro($erros["mai"])."' aria-describedby='erro_mai' name='mai' placeholder='"._("Novo endereço de email")."'>
								<div id='erro_mai' class='invalid-feedback'>".nomeErro($erros["mai"])."</div>
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
				";
			} else {
				echo "
				<div class='bg-ciano bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<h2>"._("Confirmar ativação da conta")."</h2>
					<text>". sprintf(_("Olá %s"),'<b>'.$uti['nut'].'</b>') ."<br>". sprintf(_("Enviámos um código de verificação para %s"),'<b>'.$uti_mai['mai'].'</b>') ."<br>"._("Pode demorar algum tempo até o email chegar, verifica na caixa de spam.")."</text>

					<form action='pro/registo.mai?ac=confirmar' method='post'>
						<div class='form-row align-items-center my-3'>
							<div class='col-8'>
								<input type='text' maxlength='8' class='form-control ".temErro($erros["cod"])."' aria-describedby='erro_cod' name='cod' placeholder='"._("Código de verificação")."'>
								<div id='erro_cod' class='invalid-feedback'>".nomeErro($erros["cod"])."</div>
							</div>

							<div class='col-3 align-self-start'>
								<button type='submit' class='btn btn-light text-ciano'>"._("Verificar")."</button>
							</div>
						</div>
					</form>
					";
					$tempoUltimoEmail = (strtotime(date("Y-m-d H:i:s"))-strtotime($uti_mai['ure']));

					if ($uti_mai['ree']<=2 AND $tempoUltimoEmail>=300){
						echo "<a href='pro/registo.mai?ac=reenviarMail' class='btn btn-light text-ciano'>"._("Reenviar email")."</a>";
					} else if ($uti_mai['ree']<=2){
						echo "<span data-toggle='tooltip' data-placement='bottom' title='"._("Espera 5 minutos antes de reenviar um email").".'><a class='disabled btn btn-light text-ciano'>"._("Reenviar email")."</a></span>";
					} else {
						echo "<span data-toggle='tooltip' data-placement='bottom' title='"._("Excedeste o limite de emails.")."'><a class='disabled btn btn-light text-ciano'>"._("Reenviar email")."</a></span>";
					}
					echo "
					<a href='/registo?ac=alterarMail' class='btn btn-light text-ciano'>"._("Alterar email")."</a>
				</div>
				";
			}
		} else {
			echo "
			<div class='bg-ciano bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
				<form action='/pro/registo.php' method='post' autocomplete=\"none\">
					<h1 aria-describedby='erro_campos'>"._("Registo")."</h1>

					<div class='form-group'>
						<input type='text' class='form-control ".temErro($erros["nco"])."' aria-describedby='erro_nco' name='nco' placeholder='"._("Primeiro e último nome")."'>
						<div id='erro_nco' class='invalid-feedback'>".nomeErro($erros["nco"])."</div>
					</div>

					<div class='form-group'>
						<input type='text' class='form-control ".temErro($erros["nut"])."' aria-describedby='erro_nut' name='nut' placeholder=\""._('Nome de utilizador')."\" autocomplete=\"none\">
						<div id='erro_nut' class='invalid-feedback'>".nomeErro($erros["nut"])."</div>
					</div>
					
					<div class='form-group form-row'>
						<div class='col mb-3 mb-sm-auto'>
							<input type='password' class='form-control ".temErro($erros["ppa"])."' aria-describedby='erro_ppa' name='ppa' placeholder='"._("Palavra-passe")."'>
							<div id='erro_ppa' class='invalid-feedback'>".nomeErro($erros["ppa"])."</div>
						</div>
						
						<div class='col-sm'>
							<input type='password' class='form-control ".temErro($erros["rppa"])."' aria-describedby='erro_rppa' name='rppa' placeholder='"._("Repetir a palavra-passe")."'>
							<div id='erro_rppa' class='invalid-feedback'>".nomeErro($erros["rppa"])."</div>
						</div>
					</div>

					<div class='form-check mb-3'>
						<input type='checkbox' class='form-check-input' id='check_politicas' required>
						<label class='form-check-label' for='check_politicas'>Li e concordo com as <a class='text-light' href='politicas'>Políticas e Termos</a>.</label>
					</div>

					<div class='form-group text-center'>
						<button class='text-ciano btn btn-light'>"._("Criar uma conta")."</button>
					</div>
				</form>
			</div>
			
			<div class='text-center'>
				<a href='/entrar' class='btn btn-ciano text-light'>"._("Iniciar sessão")."</a>
			</div>
			";
		}
		?>
	</body>
</html>