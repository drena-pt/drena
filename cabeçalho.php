<header class='sticky-top bg-dark shadow'>
	<nav class="px-4 px-xl-0 col-xl-6 offset-xl-3 navbar navbar-dark navbar-expand-sm">
		<a class="navbar-brand" href="/">
			<img src="imagens/logo.png" height="32" alt="" loading="lazy">
		</a>
		<button class="navbar-toggler px-0" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abtir menu">
			<span class="navbar-toggler-icon"></span>
		</button>
		<?php
		echo "
		<div class='collapse navbar-collapse justify-content-end text-end p-sm-0 pb-2 pe-1' id='menu'>
			<ul class='navbar-nav'>";
				if ($uti['car']==1){
					echo "<li class='nav-item'><a class='nav-link' href='/adm'><span class='text-rosa'>"._('Administrar')." <i class='bi bi-person-lines-fill'></i></span></a></li>";
				} else if ($uti['car']==2){
					echo "<li class='nav-item'><a class='nav-link' href='/mod'><span class='text-ciano'>"._('Moderar')." <i class='bi bi-clipboard-check'></i></span></a></li>";
				} else if ($uti['car']==3){
					echo "<li class='nav-item'><a class='nav-link' href='/mod'><span class='text-amarelo'>"._('Estatisticas')." <i class='bi bi-bar-chart-line'></i></span></a></li>";
				}
				echo "
				<li class='nav-item'><span data-toggle='modal' data-target='#modal_procurar'><a class='nav-link' href='#' data-toggle='tooltip' data-placement='bottom' title='"._('Procurar')."'><span class='d-sm-none'>"._('Procurar')." </span><i class='bi bi-search'></i></a></span></li>";
				if (!$uti){
					echo "<li class='nav-item'><a class='nav-link' href='/entrar'>"._('Entrar')."</a></li>";
				} else {
					#Botão para a futura escritura
					#<li class='nav-item'><a class='nav-link' href='/escritura.php'>"._('Escritura')."</a></li>
					echo "
					<li class='nav-item'><a class='nav-link' href='/criar' data-toggle='tooltip' data-placement='bottom' title='"._('Criar')."'><span class='d-sm-none'>"._('Criar')." </span><i class='bi bi-plus-square'></i></a></span></li>
					<li class='nav-item'><a class='nav-link' href='/definicoes' data-toggle='tooltip' data-placement='bottom' title='"._('Definições')."'><span class='d-sm-none'>"._('Definições')." </span><i class='bi bi-gear'></i></a></span></li>";
					
					$num_pedidos = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM ami WHERE b_id='".$uti["id"]."' AND sim=0"));
					
					echo "<li class='nav-item'><a href='/perfil?uti=".$uti['nut']."'><img id='fpe' data-toggle='tooltip' data-placement='bottom' title='"._('Perfil')."' class='ms-2 rounded-circle' src='".$url_media."fpe/".$uti['fpe'].".jpg' width='40' height='40'>";
					#Coloca a bolinha de aviso caso haja pedidos de amizade
					if ($num_pedidos){
						echo "<span  class='position-absolute translate-middle-x p-1 bg-primary rounded-circle'>
							<span class='visually-hidden'>Novos pedidos</span>
						</span>";
					}
					echo "</a></li>";
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
					<input type='text' class='form-control' name='oq' required placeholder='"._('Ex: utilizador, vídeo, projeto...')."'>
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