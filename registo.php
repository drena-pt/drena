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
			if ($_SESSION['pre_uti']==null){
				
				$erros = unserialize($_COOKIE["erros"]);
				function erro($erro){
					if ($erro==1){
						return "is-invalid";
					}
				}
				
				#var_dump($erros); #Mostrar erros
				
				echo "
				<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<form action='/pro/registo.php' method='post' autocomplete='off'>
						<h1 class='".erro($erros["campos"])."' aria-describedby='erro_campos'>Registo</h1>
						<div id='erro_campos' class='invalid-feedback'>
							Preenche todos os campos!
						</div>
						<div class='form-group'>
							<input type='text' class='form-control' name='nco' placeholder='O teu nome completo'>
						</div>
						<div class='form-group'>
							<input type='email' class='form-control ".erro($erros["mai"])."' aria-describedby='erro_mai' name='mai' placeholder='Email'>
							<div id='erro_mai' class='invalid-feedback'>
								O email já está a ser usado.
							</div>
						</div>
						<div class='form-group'>
							<input type='text' class='form-control ".erro($erros["nut"])."' aria-describedby='erro_nut' name='nut' placeholder='Nome de utilizador'>
							<div id='erro_nut' class='invalid-feedback'>
								O utilizador já foi registado.
							</div>
						</div>
						<div class='form-group form-row'>
							<div class='col mb-3 mb-sm-auto'>
								<input type='password' class='form-control ".erro($erros["ppa"])."' aria-describedby='erro_ppa' name='ppa' placeholder='Palavra-passe'>
							</div>
							<div class='col-sm'>
								<input type='password' class='form-control' name='rppa' placeholder='Repetir a palavra-passe'>
							</div>
							<div id='erro_ppa' class='invalid-feedback'>
								As palavras-passe são diferentes.
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
				if ($pre_uti['id']==null){
					header("Location: pro/sair.php");
					exit();
				} else if ($mai['con']==1){
					header("Location: ../");
					exit();
				}
				echo "
				<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
					<h2>Confirmar criação da conta</h2>
					<text>Enviámos um código de verificação para <b>".$mai['mai']."</b><br>Pode demorar algum tempo até chegar o mail, verifica na caixa de spam.</text>

					<form action='pro/registo.con.php' method='get'>
						<div class='form-row align-items-center my-3'>
							<div class='col-8'>
								<input type='text' maxlength='12' class='form-control' name='cod' placeholder='Código de verificação'>
							</div>
							<div class='col-3'>
								<button type='submit' class='cor1 btn btn-light'>Verificar</button>
							</div>
						</div>
					</form>
					
					";
					if ($mai['ree']==1){
						echo "<a href='pro/registo.mai.php' class='cor2 btn btn-light'>Reenviar mail</a>";
					} else {
						echo "<a class='disabled cor2 btn btn-light'>Reenviar mail</a>";
					}
					echo "
				</div>
				";
			}
			?>
		</div>
	</body>
</html>