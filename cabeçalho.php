<header class='navbar-dark sticky-top bg-dark'>
	<nav class="px-4 px-xl-0 col-xl-6 offset-xl-3 navbar navbar-expand-sm">
		<a class="navbar-brand" href="/">
			<img src="imagens/logo.png" height="32" alt="" loading="lazy">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abtir menu">
			<span fill="#fff" class="navbar-toggler-icon text-light"></span>
		</button>
		<?php
		echo "
		<div class='collapse navbar-collapse justify-content-end text-center' id='menu'>
			<ul class='navbar-nav'>";
				if ($uti['car']==1){
					echo "<li class='nav-item'><a class='nav-link' href='/adm'><span class='text-rosa'><i class='bi bi-person-lines-fill'></i>"._('Administrar')."</span></a></li>";
				} else if ($uti['car']==2){
					echo "<li class='nav-item'><a class='nav-link' href='/mod'><span class='text-ciano'><i class='bi bi-clipboard-check'></i>"._('Moderar')."</span></a></li>";
				}
				echo "
				<li class='nav-item'><span data-toggle='modal' data-target='#modal_procurar'><a class='nav-link' href='#' data-toggle='tooltip' data-placement='bottom' title='"._('Procurar')."'><i class='bi bi-search'></i></a></span></li>";
				if (!$uti){
					echo "<li class='nav-item'><a class='nav-link' href='/entrar'>"._('Entrar')."</a></li>";
				} else {
					#Botão para a futura escritura e para terminar sessão
					#<li class='nav-item'><a class='nav-link' href='/escritura.php'>"._('Escritura')."</a></li>
					##<li class='nav-item'><a class='nav-link' href='/pro/sair' data-toggle='tooltip' data-placement='bottom' title='"._('Sair')."'><i class='bi bi-box-arrow-right'></i></a></span></li>
					echo "
					<li class='nav-item'><a class='nav-link' href='/criar' data-toggle='tooltip' data-placement='bottom' title='"._('Criar')."'><i class='bi bi-plus-square'></i></a></span></li>
					<li class='nav-item'><a class='nav-link' href='/definicoes' data-toggle='tooltip' data-placement='bottom' title='"._('Definições')."'><i class='bi bi-gear'></i></a></span></li>
					<li class='nav-item'><a href='/perfil?uti=".$uti['nut']."'><img data-toggle='tooltip' data-placement='bottom' title='"._('Perfil')."' class='ms-2 rounded-circle' src='fpe/".base64_encode($uti["fot"])."' width='40' height='40'></a></li>";
				}
			echo "</ul>
		</div>
	</nav>
</header>

<!-- Modal Procurar -->
<div class='modal fade' id='modal_procurar' tabindex='-1' role='dialog' aria-labelledby='modal_procurar_label' aria-hidden='true'>
	<div class='modal-dialog' role='document'>
		<div class='modal-content bg-primary bg-gradient rounded-xl shadow p-5 text-light'>
			<form action='procurar' method='get'>
				<div class='modal-header'>
					<h1 class='modal-title' id='modal_procurar_label'>"._('Procurar')."<br></h1><br>
				</div>
				<div class='modal-body'>
					<input type='text' class='form-control' name='oq' placeholder='"._('Ex: utilizador, vídeo, projeto...')."'>
				</div>
				<div class='modal-footer'>
					<button type='submit' class='btn btn-light text-primary'>"._('Procurar')."</button>
				</div>
			</form>
		</div>
	</div>
</div>
";
?>