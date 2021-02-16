<header class='navbar-dark bg-dark'>
	<nav class="col-xl-6 offset-xl-3 navbar navbar-expand-sm">
		<a class="navbar-brand" href="/">
			<img src="imagens/logo.png" height="32" alt="" loading="lazy">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abtir menu">
			<span fill="#fff" class="navbar-toggler-icon text-light"></span>
		</button>
		<div class='collapse navbar-collapse justify-content-end text-center' id='menu'>
			<ul class='navbar-nav'>
				<?php
					if (!$uti['id']){
						echo "<li class='nav-item'><a class='nav-link' href='/entrar'>Entrar</a></li>";
					} else {
						if ($uti['adm']==1){
							echo "<li class='nav-item'><a class='nav-link' href='/adm'>Administrar</a></li>";
						}
						echo "<li class='nav-item'><a class='nav-link' href='/criar'>Criar</a></li>
						<li class='nav-item'><a class='nav-link' href='pro/sair'>Sair</a></li>
						<li class='nav-item'><a href='/perfil?uti=".$uti['nut']."' data-toggle='tooltip' data-placement='bottom' title='Perfil'><img class='mx-2 rounded-circle' src='fpe/".base64_encode($uti["fot"])."' width='40' height='40'></a></li>";
					}
				?>
			</ul>
		</div>
	</nav>
</header>