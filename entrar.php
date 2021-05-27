		<?php require('head.php') ?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
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
						return "Utilizador inválido.";break;
					case 3:
						return "A palavra-passe está errada.";break;
					case 4:
						return "Email inválido.";break;
					case 5:
						return "Excedes-te o limite de envio de emails.";break;
					case 6:
						return "As palavras-passe não podem ser diferentes.";break;
				}
			}
			#var_dump($erros); #Mostrar erros
			
			if ($_GET['ac']=='recuperar') { # Se a ação for recuperar a conta
				if ($_COOKIE['mailEnviado']){
					echo "
					<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
						<h2>Email enviado</h2>
						<text>Foi enviado um email com o link para recuperação da conta associada ao email.</text>
					</div>
					";
				} else {
					echo "
					<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
						<h2>Recuperar conta</h2>
						<text>Se não te lembras da tua palavra-passe, insere o teu email associado à conta.<br></text><br>

						<form action='/pro/registo.mai.php?ac=recuperar' method='post'>
							<div class='form-group'>
								<input type='email' class='form-control ".temErro($erros["mai"])."' aria-describedby='erro_mai' name='mai' placeholder='Endereço de email'>
								<div id='erro_mai' class='invalid-feedback'>".nomeErro($erros["mai"])."</div>
							</div>

							<div class='form-group text-center'>
								<button class='text-primary btn btn-light'>Enviar mail de recuperação</button>
							</div>
						</form>
					</div>
					";
				}
			} else if ($_GET['ac']=='alterarPasse') { # Se a ação for alterar a palavra-passe
				
				if ($_COOKIE['passeAlterada']){
					echo "
					<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
						<h2>Palavra-passe alterada</h2>
						<text>A palavra-passe foi alterada com sucesso, já podes iniciar sessão!<br></text><br>
						<div class='text-center'>
							<a href='/entrar' class='text-primary btn btn-light'>Inicia sessão</a>
						</div>
					</div>
					";
				} else {

					# Obtem informações do utilizador do GET
					$get_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));
					$get_uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$get_uti["mai"]."' AND con=1"));

					# Verifica se o código obtido pelo GET coincide com o mail do utilizador
					if ($_GET['cod'] AND $get_uti_mai['cod']==$_GET['cod']){
						echo "
						<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
							<h2>Alterar a palavra-passe</h2>
							<text>Olá <b>".$get_uti['nut']."</b>, insere a nova palavra-passe.<br></text><br>
		
							<form action='/pro/registo.mai.php?ac=alterarPasse&uti=".$_GET['uti']."&cod=".$_GET['cod']."' method='post'>
								<div class='form-group form-row'>
									<div class='col mb-3 mb-sm-auto'>
										<input type='password' class='form-control ".temErro($erros["ppa"])."' aria-describedby='erro_ppa' name='ppa' placeholder='Nova palavra-passe'>
										<div id='erro_ppa' class='invalid-feedback'>".nomeErro($erros["ppa"])."</div>
									</div>
									
									<div class='col-sm'>
										<input type='password' class='form-control ".temErro($erros["rppa"])."' aria-describedby='erro_rppa' name='rppa' placeholder='Repetir a palavra-passe'>
										<div id='erro_rppa' class='invalid-feedback'>".nomeErro($erros["rppa"])."</div>
									</div>
								</div>
		
								<div class='form-group text-center'>
									<button class='text-primary btn btn-light'>Alterar palavra-passe</button>
								</div>
							</form>
						</div>
						";
					} else {
						header("Location: /entrar");
						exit;
					}
				}
			} else { # Se não houver ação apresentar ecrã de login padrão
				echo "
				<div class='bg-primary bg-gradient rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<form action='/pro/entrar.php' method='post'>
						<h1>Entrar</h1>

						<div class='form-group'>
							<input type='text' class='form-control ".temErro($erros["nut"])."' aria-describedby='erro_nut' name='nut' placeholder='Utilizador'>
							<div id='erro_nut' class='invalid-feedback'>".nomeErro($erros["nut"])."</div>
						</div>

						<div class='form-group'>
							<input type='password' class='form-control ".temErro($erros["ppa"])."' aria-describedby='erro_ppa' name='ppa' placeholder='Palavra-passe'>
							<div id='erro_ppa' class='invalid-feedback'>".nomeErro($erros["ppa"])."</div>
						</div>

						<div class='form-group text-center'>
							<button class='text-primary btn btn-light'>Iniciar sessão</button>
						</div>
					</form>
				</div>
				
				<div class='text-center'>
					";
					if ($erros["ppa"]){echo "<a href='?ac=recuperar' class='btn btn-light text-primary'>Recuperar conta</a>";}
					echo "
					<a href='/registo' class='btn btn-primary'>Cria uma conta</a>
				</div>
				";
			}
			?>
		</div>
	</body>
</html>