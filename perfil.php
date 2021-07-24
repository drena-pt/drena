		<?php 
		require('head.php');
		$uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));
		
		if ($uti_perfil){
			echo "
			<meta property='og:image' content='".$url_site."fpe/".base64_encode($uti_perfil['fot'])."'>
			<meta property='og:description' content='".$uti_perfil['nut']."'>
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
							"._('Bem vindo!')."
							<button type='button' class='btn close text-light' data-toggle='collapse' href='#bem_vindo' role='button' aria-expanded='false' aria-controls='bem_vindo'>
								<svg class='bi' width='1em' height='1em'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#x-square-fill'/></svg>
							</button>
						</text>
						<p>"._('Obrigado por te registares na drena, fica à vontade para partilhares as tuas coisas.')."</p>
					</div>
				</div>
				";
			}
			
			echo "
			<div class='shadow p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3'>
				<div class='bg-primary bg-cover text-light p-xl-5 p-4 caixa-perfil-foto'>";
			
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
					<span id='fpe_carregar'>
						"._('Alterar foto')."
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
					echo _('Adicionar conhecido')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-plus-fill'/></svg></a>";
				} else {
					if ($ami_uti['sim']==1){ #Se já forem conhecidos
						echo _('São conhecidos')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-check-fill'/></svg></a>
						<script>
						$('#ami').hover(function(){
							$(this).html(\""._('Remover conhecido')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-x-fill'/></svg>\");
							}, function(){
							$(this).html(\""._('São conhecidos')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-check-fill'/></svg>\");
						});
						</script>";
					} else {
						if ($ami_uti['a_id']==$uti['id']){
							echo _('Pedido enviado')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-fill'/></svg></a>
							<script>
							$('#ami').hover(function(){
								$(this).html(\""._('Cancelar pedido')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-x-fill'/></svg>\");
								}, function(){
								$(this).html(\""._('Pedido enviado')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-fill'/></svg>\");
							});
							</script>";
						} else if ($ami_uti['b_id']==$uti['id']){
							echo _('Aceitar pedido')." <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#person-check-fill'/></svg></a>";
						}
					}
				}
			}
			
			echo "<h1>".$uti_perfil['nut']."</h1>
			<text class='h2'>".$uti_perfil['nco']."</text>";
			
			function mes($x){
				switch ($x){
					case 1: return _('janeiro'); break;
					case 2: return _('fevereiro'); break;
					case 3: return _('março'); break;
					case 4: return _('abril'); break;
					case 5: return _('maio'); break;
					case 6: return _('junho'); break;
					case 7: return _('julho'); break;
					case 8: return _('agosto'); break;
					case 9: return _('setembro'); break;
					case 10: return _('outubro'); break;
					case 11: return _('novembro'); break;
					case 12: return _('dezembro');  break;
				}
			}
			
			$dat = strtotime($uti_perfil['dcr']);

			echo "<br>".sprintf(_('Utilizador desde %s de'),mes(date('m',$dat)))." ".date('Y',$dat)."</div>";

			$sql_conhecidos = "SELECT a_id, b_id FROM ami WHERE a_id='".$uti_perfil["id"]."' AND sim='1' OR b_id='".$uti_perfil["id"]."' AND sim='1' ORDER by b_dat DESC";
			$num_conhecidos = mysqli_num_rows(mysqli_query($bd, $sql_conhecidos));
			$conhecidos = mysqli_fetch_assoc(mysqli_query($bd, $sql_conhecidos));
			
			$pedidos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE b_id='".$uti_perfil["id"]."' AND sim=0 LIMIT 1"));
			function mini_nut($nut){
				if (strlen($nut)>=12){
					return (substr($nut, 0, 10)."…");
				} else {
					return ($nut);
				}
			}
			
			$uti_perfil_projetos = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM pro WHERE uti='".$uti_perfil['id']."';"));
			$uti_perfil_audios = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med WHERE tip=2 AND uti='".$uti_perfil['id']."';"));
			$uti_perfil_imagens = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med WHERE tip=3 AND uti='".$uti_perfil['id']."';"));
			$uti_perfil_videos = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med WHERE tip=1 AND uti='".$uti_perfil['id']."';"));

			if ($uti_perfil_projetos!=0 OR $uti_perfil_audios!=0 OR $uti_perfil_imagens!=0 OR $uti_perfil_videos!=0){
			echo "
			<div class='bg-dark text-light p-xl-5 p-4'>
				<section class='row'>";
				if ($uti_perfil_projetos!=0){
					echo "<a href='#conteudo' onclick='mostrarConteudo(0)' class='text-decoration-none col h5 text-center text-light'>"._('Projetos')." <span class='badge rounded-pill bg-gradient bg-light text-dark'>".$uti_perfil_projetos."</span></a>";
				}
				if ($uti_perfil_audios!=0){
					echo "<a href='#conteudo' onclick='mostrarConteudo(2)' class='text-decoration-none col h5 text-center text-light'>"._('Áudios')." <span class='badge rounded-pill bg-gradient bg-rosa'>".$uti_perfil_audios."</span></a>";
				}
				if ($uti_perfil_imagens!=0){
					echo "<a href='#conteudo' onclick='mostrarConteudo(3)' class='text-decoration-none col h5 text-center text-light'>"._('Imagens')." <span class='badge rounded-pill bg-gradient bg-ciano'>".$uti_perfil_imagens."</span></a>";
				}
				if ($uti_perfil_videos!=0){
					echo "<a href='#conteudo' onclick='mostrarConteudo(1)' class='text-decoration-none col h5 text-center text-light'>"._('Vídeos')." <span class='badge rounded-pill bg-gradient bg-primary'>".$uti_perfil_videos."</span></a>";
				}
			echo "</section>
			</div>";
			}

			if ($conhecidos OR $pedidos AND $uti_perfil['nut']==$_SESSION["uti"]){
				echo "<div class='bg-dark text-light p-xl-5 px-4'>";

				if ($conhecidos){
					if ($result = $bd->query($sql_conhecidos)) {
						echo "<text class='h5'>"._('Lista de conhecidos')." <span class='badge rounded-pill bg-gradient bg-light text-dark'>".$num_conhecidos."</span></text>
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
						echo "<text class='h5'>"._('Pedidos')."</text>
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

			echo "
			<div class='p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3' id='conteudo'></div>
			<script>
			function mostrarConteudo(tip){
				$('#conteudo').load('/pro/media.php?ac=lista&tip='+tip+'&uti=".$_GET["uti"]."', function(){ location.href = '#conteudo'; });
			}
			</script>
			";

		} else {
			echo "<h2 class='my-4 text-center'>O utilizador não existe</h2>";
		}
		?>
		</div>
	</body>
</html>