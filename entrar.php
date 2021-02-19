		<?php require('head.php') ?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
			<style>
			:root{
				--cor1: #6600ff;
				--cor2: #330099;
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
						return "Utilizador inválido.";break;
					case 3:
						return "A palavra-passe está errada.";break;
				}
			}
			
			#var_dump($erros); #Mostrar erros
			
			echo "
			<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
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
						<button class='cor1 btn btn-light'>Iniciar sessão</button>
					</div>
				</form>
			</div>
			
			<h6 class='text-center'>ou <a href='/registo' class='btn btn-dark'>Cria uma conta</a></h6>
			";
			?>
		</div>
	</body>
</html>