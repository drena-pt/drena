		<?php 
		require('head.php');
		$uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));
		
		if ($uti_perfil){
			echo "
			<meta property='og:image' content='https://2.drena.xyz/fpe/".base64_encode($uti_perfil['fot'])."'>
			<meta property='og:description' content='Perfil de ".$uti_perfil['nut']."'>
			";
		} else {
			echo "<meta property='og:description' content='Utilizador não encontrado.'>";
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<?php
		if ($uti_perfil){
			echo "
			<style>
			:root{
				--perfil-foto: url('/fpe/".base64_encode($uti_perfil["fot"])."');
			}
			</style>
			";
			if ($_COOKIE['bem-vindo']){
				echo "
				<div id='bem_vindo' class='collapse show'>
					<div class='bg-rosa bg-gradient shadow p-4 p-xl-5 rounded-xl my-4 col-xl-4 col-sm-8 offset-xl-4 offset-sm-2'>
						<text class='h2'>
							Bem vindo!
							<button type='button' class='btn close text-light' data-toggle='collapse' href='#bem_vindo' role='button' aria-expanded='false' aria-controls='bem_vindo'>
								<svg class='bi' width='1em' height='1em'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#x-square-fill'/></svg>
							</button>
						</text>
						<p>Obrigado por te registares na drena,<br>fica à vontade para partilhares os teus projetos.</p>
					</div>
				</div>
				";
			}
			
			echo "
			<div class='shadow p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3'>
				<div class='bg-primary text-light p-xl-5 p-4 caixa-perfil-foto'>";
			
			if ($uti_perfil['id']==$uti['id']){
				echo "
				<style>
				#fpe_a_carregar {
					text-align: center;
					padding: 0 20px;
					max-height: 24px;
				}
				.box {
					position: relative;
					width: 16px;
					height: 16px;
					margin: 4px;
					display: inline-block;
					background-color: #000;
				}
				</style>
				<label for='fpe' class='float-end btn btn-light' style='cursor:pointer;'>
					<span id='fpe_carregar'>Alterar foto
						<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#image'/></svg>
					</span>
					<div style='display:none;' id='fpe_a_carregar' data-placement='bottom' data-toggle='tooltip' title='A carregar...'>
						<div class='box'></div>
					</div>
				</label>
				<form hidden enctype='multipart/form-data' action='pro/enviar_fpe.php' method='post'>
					<input type='file' id='fpe' name='fpe'/>
					<input type='submit'/>
				</form>
				<script>
				$('#fpe').change(function(objEvent) {
					var objFormData = new FormData();
					var objFile = $(this)[0].files[0];
					objFormData.append('fpe', objFile);
					$('#fpe_a_carregar').show();
					$('#fpe_carregar').hide();
					$.ajax({
						url: 'pro/enviar_fpe.php',
						type: 'POST',
						contentType: false,
						data: objFormData,
						processData: false,
						success: function(php_script_response){
							if (php_script_response){
								alert(php_script_response);
							}
							location.reload();
						}
					});
				});
				anime({
					targets: '.box',
					keyframes: [
						{translateX: 16, rotate: '90deg'},
						{translateX: 0, rotate: '0deg'},
						{translateX: -16, rotate: '-90deg'},
						{translateX: 0, rotate: '0deg'}
					],
					duration: '3500',
					loop: true,
					easing: 'easeInOutBack',
					direction: 'normal'
				});
				</script>
				";
			} else if ($uti){
				$ami_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE (a_id='".$uti["id"]."' AND b_id='".$uti_perfil["id"]."') OR (a_id='".$uti_perfil["id"]."' AND b_id='".$uti["id"]."')"));
				echo "<a id='ami' class='float-end btn btn-light' href='pro/ami.php?uti=".$uti_perfil['nut']."'>";
				if (!$ami_uti['id']){
					echo "Adicionar conhecido <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-plus-fill'/></svg></a>";
				} else {
					if ($ami_uti['sim']==1){ #Se já forem conhecidos
						echo "São conhecidos <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-check-fill'/></svg></a>
						<script>
						$('#ami').hover(function(){
							$(this).html(\"Remover conhecido <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-x-fill'/></svg>\");
							}, function(){
							$(this).html(\"São conhecidos <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-check-fill'/></svg>\");
						});
						</script>";
					} else {
						if ($ami_uti['a_id']==$uti['id']){
							echo "Pedido enviado <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-fill'/></svg></a>
							<script>
							$('#ami').hover(function(){
								$(this).html(\"Cancelar pedido <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-x-fill'/></svg>\");
								}, function(){
								$(this).html(\"Pedido enviado <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-fill'/></svg>\");
							});
							</script>";
						} else if ($ami_uti['b_id']==$uti['id']){
							echo "Aceitar pedido <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-check-fill'/></svg></a>";
						}
					}
				}
			}
			
			echo "<h1>".$uti_perfil['nut']."</h1>
			<text class='h2'>".$uti_perfil['nco']."</text>";
			
			function mes($x){
				switch ($x){
					case 1: return "janeiro"; break;
					case 2: return "fevereiro"; break;
					case 3: return "março"; break;
					case 4: return "abril"; break;
					case 5: return "maio"; break;
					case 6: return "junho"; break;
					case 7: return "julho"; break;
					case 8: return "agosto"; break;
					case 9: return "setembro"; break;
					case 10: return "outubro"; break;
					case 11: return "novembro"; break;
					case 12: return "dezembro";  break;
				}
			}

			function ano($x){
				if (date('Y')==$x){
					return ".";
				} else {
					return " de ".$x.".";
				}
			}
			
			$dat = strtotime($uti_perfil['dcr']);
			echo "<br>Utilizador desde ".mes(date('m',$dat)).ano(date('Y',$dat))."</div>";

			$conhecidos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE a_id='".$uti_perfil["id"]."' AND sim=1 OR b_id='".$uti_perfil["id"]."' AND sim=1 LIMIT 1"));
			$pedidos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE b_id='".$uti_perfil["id"]."' AND sim=0 LIMIT 1"));
			function mini_nut($nut){
				if (strlen($nut)>=12){
					return (substr($nut, 0, 10)."…");
				} else {
					return ($nut);
				}
			}
			
			if ($conhecidos OR $pedidos AND $uti_perfil['nut']==$_SESSION["uti"]){
				echo "<div class='bg-gradient bg-dark text-light p-xl-5 p-4'>";

				if ($conhecidos){
					if ($result = $bd->query("SELECT a_id, b_id FROM ami WHERE a_id='".$uti_perfil["id"]."' AND sim='1' OR b_id='".$uti_perfil["id"]."' AND sim='1' ORDER by b_dat DESC")) {
						echo "<text class='h5'>Conhecidos</text>
						<div class='row my-2'>";
						while ($row = $result->fetch_row()) {
							echo "<div class='col-md-2 col-4 my-3 text-center'>";
							if ($row[0]==$uti_perfil["id"]){
								$uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[1]."'"));
								echo "<a class='perfil' href='/perfil?uti=".$uti_b['nut']."'>
								<img class='mx-1 rounded-circle' src='fpe/".base64_encode($uti_b['fot'])."' width='64'><br>".mini_nut($uti_b['nut'])."</a>";
							} else {
								$uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[0]."'"));
								echo "<a class='perfil' href='/perfil?uti=".$uti_a['nut']."'>
								<img class='mx-1 rounded-circle' src='fpe/".base64_encode($uti_a['fot'])."' width='64'><br>".mini_nut($uti_a['nut'])."</a>";
							}
							echo "</div>";
						}
						echo "</div>";
						$result->close();
					}
				}

				if ($uti_perfil['nut']==$_SESSION["uti"] AND $pedidos){
					if ($result = $bd->query("SELECT a_id FROM ami WHERE b_id='".$uti["id"]."' AND sim='0' ORDER by id DESC")){
						echo "<text class='h5'>Pedidos</text>
						<div class='row my-2'>";
						while ($row = $result->fetch_row()) {
							$uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[0]."'"));
							echo "<div class='col-md-2 col-4 my-3 text-center'>
							<a class='perfil' href='/perfil?uti=".$uti_a['nut']."'>
							<img class='mx-1 rounded-circle' src='fpe/".base64_encode($uti_a['fot'])."' width='64'><br>".mini_nut($uti_a['nut'])."</a>
							</div>";
						}
						echo "</div>";
						$result->close();
					}
				}
				echo "</div>";
			}
			echo "</div>";

			$projetos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE uti='".$uti_perfil['id']."' LIMIT 1"));
			
			if ($projetos AND $result = $bd->query("SELECT * FROM pro WHERE uti='".$uti_perfil['id']."'")) {
				echo "
				<div class='p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3'>
				<div class='p-xl-5 p-4'><h1>Projetos</h1></div>
				<div class='row row-cols-1 row-cols-md-2'>
				";

				while ($row = $result->fetch_assoc()) {
					if (!$row['tit']){$pro_tit='Projeto';}else{$pro_tit=$row['tit'];}
					echo"
					<div class='col'><a class='text-decoration-none' href='/projeto?id=".base64_encode($row['id'])."' ><div id='cartao_1' class='bg-".numeroParaCor($row['cor'])." text-dark p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h3 class='text-light'>".$pro_tit."</h3>
					</div></a></div>
					";
				}
				$result->close();

				echo "</div>
				</div>";
			}

		} else {
			echo "<h2 class='my-4 text-center'>O utilizador não existe</h2>";
		}
		?>
		</div>
	</body>
</html>