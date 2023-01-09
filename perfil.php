<?php
$site_tit = 'off';
require('head.php');
$uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));



if ($uti_perfil){

	#Se houver uma sessão carrega o script da API
	if ($uti){
		echo "<script src='./js/api.min.js'></script>";
	}

	echo "
	<meta property='og:image' content='".$url_media."fpe/".$uti_perfil['fpe'].".jpg'>
	<meta property='og:description' content='".$uti_perfil['nut']."'>
	<title>".$uti_perfil['nut']." - drena</title>
	";
} else {
	echo "
	<meta property='og:description' content='Utilizador não encontrado.'>
	<title>drena</title>";
}
?>
	</head>
	<body>
	<?php require('cabeçalho.php'); ?>
	<?php
	if ($uti_perfil){
		echo "
		<style>
		:root{
			--perfil-foto: url('".$url_media."fpe/".$uti_perfil['fpe'].".jpg');
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
							<i class='bi bi-x-square-fill'></i>
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
			<button data-toggle='modal' data-target='#modal_fpe' class='btn btn-light float-end'>
				<span id='fpe_carregar'>
					"._('Alterar foto')." <i class='bi bi-image'></i>
				</span>
				<span id='fpe_a_carregar' style='display:none;'>
					A carregar⠀
					<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>
				</span>
			</button>

			<div class='modal fade' id='modal_fpe' tabindex='-1' role='dialog' aria-labelledby='modal_fpe_label' aria-hidden='true'>
				<div class='modal-dialog' role='document'>
					<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
						<form action='#' method='post'>
							<div class='modal-header mb-3'>
								<h2 class='modal-title' id='modal_fpe_label'>Alterar foto<br></h2><br>
								<button type='button' class='text-light btn-close' data-dismiss='modal' aria-label='"._('Fechar')."'><i class='bi bi-x-lg'></i></button>
							</div>
							<div class='modal-body'>
								";
								$pesquisa = "SELECT * FROM uti_fpe WHERE uti='".$uti['id']."' ORDER BY den DESC";
								if ($resultado = $bd->query($pesquisa)){
									echo "<div class='m-0 row row-cols-3 mw-100'>
									
									<div class='col p-2 align-self-center'>
										<label role='button' for='fpe_input'>
											<div class='rounded-xl bg-light text-dark text-center py-3'>
												"._('Carregar foto')."<i class='bi bi-upload'></i>
											</div>
										</label>
									</div>
									";
									while ($campo = $resultado->fetch_assoc()){
										$fpe_atual = NULL;
										if ($uti['fpe']==$campo['id']){
											$fpe_atual = 'border';
										}
										echo "
										<div class='col p-md-2 p-1'>
											<img id='fpe_".$campo['id']."' role='button' onclick=\"mudar_fpe('".$campo['id']."')\" class='".$fpe_atual." rounded-xl w-100' src='".$url_media."fpe/".$campo['id'].".jpg'>
										</div>
										";
									}
									$resultado->free();
									echo "</div>";
								}
								echo "
							</div>
						</form>
					</div>
				</div>
			</div>

			<form hidden enctype='multipart/form-data'>
				<input type='file' accept='image/*' id='fpe_input' name='fpe'/>
				<input type='submit'/>
			</form>

			<script>
			function mudar_fpe(fpe){
				result = api('fpe', {'ac':'mudar','fpe':fpe});
				if (result['est']=='sucesso'){
					document.documentElement.style.setProperty('--perfil-foto', 'url('+result['fpe']+')');
					$('#fpe').attr('src',result['fpe']);
					$('#modal_fpe').modal('hide');
					$('*[id*=fpe]:visible').each(function() {
						$(this).removeClass('border');
					});
					$('#fpe_'+fpe).addClass('border');
				}
			}

			$('#fpe_input').change(function() {
				$('#fpe_a_carregar').show();
				$('#fpe_carregar').hide();
				$('#modal_fpe').modal('hide');
				var objFormData = new FormData();
				var objFile = $(this)[0].files[0];
				setTimeout(function(){
					objFormData.append('fpe', objFile);
					result = api('fpe', objFormData, false, false);
					if (result['est']=='sucesso'){
						document.documentElement.style.setProperty('--perfil-foto', 'url('+result['fpe']+')');
						$('#fpe').attr('src',result['fpe']);
						$('#fpe_a_carregar').hide();
						$('#fpe_carregar').show();
					}
				}, 800);
			});
			</script>
			";
		} else if ($uti){
			echo "<button id='btn_ami' class='float-end btn btn-light'></button>
			
			<script>
			ami_result = api('ami', {'ac':'ob','uti':'".$uti_perfil['nut']."'});
			var ami = ami_result['est'];
			console.debug('Nivel de amizade '+ami);

			$('#btn_ami').click(function(){
				ami_result = api('ami', {'uti':'".$uti_perfil['nut']."'});
				ami = ami_result['est'];
				console.debug('Nivel de amizade '+ami);
				btn_ami();
			});

			function btn_ami(){
				switch(ami){
				  case '0':
					$('#btn_ami').html('Adicionar conhecido <i class=\"bi bi-person-fill-add\"></i>')
					.unbind('mouseenter mouseleave'); break;
				  case '1':
					text_d = 'São conhecidos <i class=\"bi bi-person-fill-check\"></i>';
					text_h = 'Remover conhecido <i class=\"bi bi-person-fill-x\"></i>';
					$('#btn_ami').html(text_d)
					.hover(function(){ $(this).html(text_h); }, function(){ $(this).html(text_d); }); break;
				  case '2':
					text_d = 'Pedido enviado <i class=\"bi bi-person-fill\"></i>';
					text_h = 'Cancelar pedido <i class=\"bi bi-person-fill-x\"></i>';
					$('#btn_ami').html(text_d)
					.hover(function(){ $(this).html(text_h); }, function(){ $(this).html(text_d); }); break;
				  case '3':
					$('#btn_ami').html('Aceitar pedido <i class=\"bi bi-person-fill-check\"></i>')
					.unbind('mouseenter mouseleave'); break;
				}
			}
			btn_ami();
			</script>
			";
		}
		
		echo "<h1 style='font-size:calc(2.2rem + 1.4vw);'>".$uti_perfil['nut']."</h1>
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
				echo "<a href='#conteudo' onclick='mostrarConteudo(0)' class='col-6 col-sm-3 text-decoration-none text-center text-light'>"._('Projetos')." <span class='badge rounded-pill bg-gradient bg-light text-dark'>".$uti_perfil_projetos."</span></a>";
			}
			if ($uti_perfil_audios!=0){
				echo "<a href='#conteudo' onclick='mostrarConteudo(2)' class='col-6 col-sm-3 text-decoration-none text-center text-light'>"._('Áudios')." <span class='badge rounded-pill bg-gradient bg-rosa'>".$uti_perfil_audios."</span></a>";
			}
			if ($uti_perfil_imagens!=0){
				echo "<a href='#conteudo' onclick='mostrarConteudo(3)' class='col-6 col-sm-3 text-decoration-none text-center text-light'>"._('Imagens')." <span class='badge rounded-pill bg-gradient bg-ciano'>".$uti_perfil_imagens."</span></a>";
			}
			if ($uti_perfil_videos!=0){
				echo "<a href='#conteudo' onclick='mostrarConteudo(1)' class='col-6 col-sm-3 text-decoration-none text-center text-light'>"._('Vídeos')." <span class='badge rounded-pill bg-gradient bg-primary'>".$uti_perfil_videos."</span></a>";
			}
		echo "</section>
		</div>";
		}

		if ($conhecidos OR $pedidos AND $uti_perfil['nut']==$_SESSION["uti"]){
			echo "<div class='bg-dark text-light p-xl-5 p-4'>";

			if ($conhecidos){
				if ($result = $bd->query($sql_conhecidos)) {
					echo "<text class='h5'>"._('Lista de conhecidos')." <span class='badge rounded-pill bg-gradient bg-light text-dark'>".$num_conhecidos."</span></text>
					<div class='row my-2'>";
					while ($row = $result->fetch_row()) {
						echo "<div class='col-md-2 col-4 my-3 text-center'>";
						if ($row[0]==$uti_perfil["id"]){
							$uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[1]."'"));
							echo "<a class='perfil' href='/perfil?uti=".$uti_b['nut']."'>
							<img class='mx-1 rounded-circle' src='".$url_media."fpe/".$uti_b['fpe'].".jpg' width='64'><br>".mini_nut($uti_b['nut'])."</a>";
						} else {
							$uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[0]."'"));
							echo "<a class='perfil' href='/perfil?uti=".$uti_a['nut']."'>
							<img class='mx-1 rounded-circle' src='".$url_media."fpe/".$uti_a['fpe'].".jpg' width='64'><br>".mini_nut($uti_a['nut'])."</a>";
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
						<img class='mx-1 rounded-circle' src='".$url_media."fpe/".$uti_a['fpe'].".jpg' width='64'><br>".mini_nut($uti_a['nut'])."</a>
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
	</body>
</html>