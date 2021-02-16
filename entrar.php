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
			<div class='gradiente rounded-xl shadow p-5 text-light my-4 col-xl-4 offset-xl-4 col-sm-8 offset-sm-2'>
				<form action='/pro/entrar.php' method='post'>
					<h1>Entrar</h1>
					<div class='form-group'>
						<input type='text' class='form-control' name='nut' placeholder='Utilizador'>
					</div>
					<div class='form-group'>
						<input type='password' class='form-control' name='ppa' placeholder='Palavra-passe'>
					</div>
					<div class="form-group text-center">
						<button class="cor1 btn btn-light">Iniciar sessão</button>
					</div>
				</form>
			</div>
			
			<h6 class='text-center'>ou <a href='/registo' class="btn btn-dark">Cria uma conta</a></h6>
		</div>
	</body>
</html>