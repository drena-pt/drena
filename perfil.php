<?php
$site_tit = 'off';
require('head.php');
$uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));

if ($uti_perfil){
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
		<script src='/js/api.min.js'></script>
	</head>
	<body>
	<?php require('header.php'); ?>
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
					<div class='d-flex justify-content-between'>
						<text class='h2'>"._('Bem vindo!')."</text>
						<button type='button' class='btn-close p-2' data-bs-toggle='collapse' href='#bem_vindo'></button>
					</div>
					<p>"._('Obrigado por te registares na drena, fica à vontade para partilhares as tuas coisas.')."</p>
				</div>
			</div>
			";
		}
		
		#INICIO - Caixa do utilizador
		echo "
		<div class='shadow p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3'>
			<div class='bg-primary bg-cover text-light p-xl-5 p-4 caixa-perfil-foto'>";

		if ($uti_perfil['id']==$uti['id']){
			echo "
			<button data-bs-toggle='modal' data-bs-target='#modal_fpe' class='btn btn-light float-end ms-2'>
				<span id='fpe_carregar'>
					<i class='bi bi-image'></i>"._('Alterar foto')."
				</span>
				<span id='fpe_a_carregar' style='display:none;'>
					A carregar⠀
					<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>
				</span>
			</button>

			<div class='modal fade' id='modal_fpe' tabindex='-1' role='dialog' aria-labelledby='modal_fpe_label' aria-hidden='true'>
				<div class='modal-dialog' role='document'>
					<div class='modal-content bg-cinza bg-gradient rounded-xl shadow p-4 p-xl-5 text-light'>
						<div class='modal-header mb-3'>
							<h2 class='modal-title' id='modal_fpe_label'>"._('Alterar foto')."<br></h2><br>
							<button type='button' class='btn-close p-2' data-bs-dismiss='modal'></button>
						</div>
						<div class='modal-body'>
							";
							$pesquisa = "SELECT * FROM uti_fpe WHERE uti='".$uti['id']."' ORDER BY den DESC";
							if ($resultado = $bd->query($pesquisa)){
								echo "<div class='m-0 row row-cols-3 mw-100'>
								
								<div class='col p-2 align-self-center'>
									<label role='button' for='fpe_input' class='rounded-xl bg-light text-dark text-center py-3 w-100'>
										<i class='bi bi-upload'></i><br>"._('Carregar foto')."
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
		}

		#INICIO - Emblema
		if ($uti_perfil['car']==1){
			echo "<span class='badge bg-rosa float-end'><i class='bi bi-shield-check'></i>"._('Administrador')."</span>";
		} else if ($uti_perfil['car']==2){
			echo "<span class='badge bg-ciano float-end'><i class='bi bi-shield-check'></i>"._('Moderador')."</span>";
		} else if ($uti_perfil['car']==3){
			echo "<span class='badge bg-amarelo float-end'><i class='bi bi-shield-check'></i>"._('Segurança')."</span>";
		}
		if ($uti_perfil['id']==100){ #Emblema de utilizador número 100 para o Eemeli
			echo "<span style='background-color:#ffe85a;' class='badge text-dark float-end'>Nº100</span>";
		}
		#FIM - Emblema
		
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

		function mini_nut($nut){
			if (strlen($nut)>=12){
				return (substr($nut, 0, 10)."…");
			} else {
				return ($nut);
			}
		}

		#Secção conhecidos
		$sql_conhecidos = "SELECT a_id, b_id FROM ami WHERE a_id='".$uti_perfil["id"]."' AND sim='1' OR b_id='".$uti_perfil["id"]."' AND sim='1' ORDER by b_dat DESC";
		$num_conhecidos = mysqli_num_rows(mysqli_query($bd, $sql_conhecidos));
		$poucos_conhecidos = mysqli_query($bd, $sql_conhecidos." LIMIT 6");
		$todos_conhecidos = mysqli_query($bd, $sql_conhecidos);
		$pedidos = mysqli_query($bd, "SELECT a_id FROM ami WHERE b_id='".$uti_perfil["id"]."' AND sim=0");
		$num_pedidos = mysqli_num_rows($pedidos);

		#Não mostrar secção de conhecidos se não houver sessão iniciada e o perfil não tiver conhecidos
		if (!(!$num_conhecidos AND !$uti)){
			#INICIO - Secção escura inferior
			echo "<section class='bg-dark text-light p-4 p-xl-5'>";

			#INÍCIO - Secção pedidos
			#Mostrar se tiver sessão iniciada e pedidos
			if ($uti_perfil['nut']==$_SESSION["uti"] AND $num_pedidos){
				echo "<section id='section_pedidos' class='pb-2'>
					"._('Pedidos')."
					<div id='lista_pedidos' class='row row-cols-1 row-cols-md-2'>";
					while ($pedido = $pedidos->fetch_row()) {
						$uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$pedido[0]."'"));
						echo "
						<div id='pedido_".$uti_a['nut']."' class='col pt-0 pb-2 p-1'>
							<div class='alert border-0 bg-primary bg-opacity-25 d-flex align-items-center p-3 m-0' role='alert'>
								<a class='perfil' href='/u/".$uti_a['nut']."'>
								<img class='rounded-circle me-2' src='".$url_media."fpe/".$uti_a['fpe'].".jpg' width='40'>
								".$uti_a['nut']."</a>
								<button onclick='pedido_ami(\"".$uti_a['nut']."\",\"".$uti_a['fpe']."\")' class='btn btn-primary ms-auto m-0 me-2'><i class='bi bi-person-check'></i>"._('Aceitar pedido')."</button>
							</div>
						</div>";
					}
					echo "</div>
				</section>";

				$append_lista_ami = '
				<div class="me-2 my-2 text-center"><a class="perfil" href="/u/\'+uti+\'">
					<img class="mx-3 rounded-circle" src="'.$url_media.'fpe/\'+fpe+\'.jpg" width="64"><br>\'+uti+\'
				</a></div>';

				echo "
				<script>
				num_pedidos = ".$num_pedidos.";
				function pedido_ami(uti,fpe){
					result = api('ami', {'uti':uti});
					if (result['est']==1){
						var num_ami = parseInt($('#num_ami').text());
						$('#num_ami').html(num_ami+1);
						$('#pedido_'+uti).remove();
						$('#lista_ami').prepend('".trim(preg_replace('/\s\s+/', ' ', $append_lista_ami))."');
						var pedidos = $('#lista_pedidos').text();
						num_pedidos--;
						if (!num_pedidos){
							$('#section_pedidos').remove();
						}
					}
				}
				</script>
				";
			}
			#FIM - Secção pedidos

			#INÍCIO - Lista pequena
			echo "<section class='collapse show' id='section_ami'>
			<div class='d-flex align-items-center justify-content-between'>";
			if ($num_conhecidos){
				echo "<div><span id='num_ami'>".$num_conhecidos."</span>";
				if ($num_conhecidos==1){
					echo " "._("Conhecido")."<br>";
				} else {
					echo " "._("Conhecidos")."<br>";
				}
				while ($row = $poucos_conhecidos->fetch_row()){
					if ($row[0]==$uti_perfil["id"]){
						$campo_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[1]."'"));
					} else {
						$campo_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[0]."'"));
					}
					echo "<a href='/u/".$campo_uti['nut']."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$campo_uti['nut']."'><img src='".$url_media."fpe/".$campo_uti['fpe'].".jpg' class='mb-2 me-2 rounded-circle' width='32'></a>";
				}
				if ($num_conhecidos>6){
					echo "<button data-bs-toggle='collapse' data-bs-target='#section_ami' aria-expanded='false' class='badge rounded-pill bg-light text-dark'>+".($num_conhecidos-6)."</button>";
				}
				echo "</div>";
			} else {
				echo _("Ainda não tem conhecidos");
				if ($uti['id']==$uti_perfil['id']){ #Mostrar o botão de procurar caso seja o próprio utilizador
					echo "<button class='m-0 btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal_procurar'><i class='bi bi-person-plus-fill'></i>"._("Procurar utilizadores")."</button>";
				}
			}

			#Se o utilizador tiver sessão iniciada e não for o próprio, mostra o botão de adicionar amigo
			if ($uti AND $uti['id']!=$uti_perfil['id']){
				echo "<button id='btn_ami' class='my-1 btn col-auto'></button>
				
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
						$('#btn_ami').html('<i class=\"bi bi-person-add\"></i>"._('Adicionar conhecido')."')
						.unbind('mouseenter mouseleave')
						.removeClass('btn-vermelho').addClass('btn-light'); break;
					  case '1':
						text_d = '<i class=\"bi bi-person-check\"></i>"._('São conhecidos')."';
						text_h = '<i class=\"bi bi-person-x\"></i>"._('Remover conhecido')."';
						$('#btn_ami').html(text_d)
						.hover(function(){
							$(this).html(text_h).removeClass('btn-primary').addClass('btn-vermelho');
						}, function(){
							$(this).html(text_d).removeClass('btn-vermelho').addClass('btn-primary');
						}).removeClass('btn-light').addClass('btn-primary'); break;
					  case '2':
						text_d = '<i class=\"bi bi-person\"></i>"._('Pedido enviado')."';
						text_h = '<i class=\"bi bi-person-x\"></i>"._('Cancelar pedido')."';
						$('#btn_ami').html(text_d)
						.hover(function(){
							$(this).html(text_h).removeClass('btn-light').addClass('btn-vermelho');
						}, function(){
							$(this).html(text_d).removeClass('btn-vermelho').addClass('btn-light');
						}).removeClass('btn-vermelho').addClass('btn-light'); break;
					  case '3':
						$('#btn_ami').html('<i class=\"bi bi-person-check\"></i>"._('Aceitar pedido')."')
						.unbind('mouseenter mouseleave')
						.addClass('btn-primary'); break;
					}
				}
				btn_ami();
				</script>
				";
			}
			echo "</div></section>";
			#FIM - Lista pequena
			
			#INICIO - Secção todos os conhecidos
			if ($num_conhecidos>6){
				echo "
				<section id='section_ami' class='bg-dark collapse'>
					"._("Todos os conhecidos")."
					<button type='button' class='btn-close p-2' data-bs-toggle='collapse' data-bs-target='#section_ami'></button>
					<section class='row m-0 mw-100 row-cols-4 row-cols-md-6'>";
					while ($row = $todos_conhecidos->fetch_row()){
						if ($row[0]==$uti_perfil["id"]){
							$row_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[1]."'"));
						} else {
							$row_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row[0]."'"));
						}
						echo "<div class='col p-0 my-3 text-center'><a class='perfil' href='/u/".$row_uti['nut']."'>
						<img class='rounded-circle' src='".$url_media."fpe/".$row_uti['fpe'].".jpg' width='64'><br>".mini_nut($row_uti['nut'])."</a>
						</div>";
					}
					echo "</div>
				</section>";
			}
			#FIM - Secção todos os conhecidos
		}
		echo "</div>";
		#FIM - Caixa do utilizador

		$perfil_num_med = mysqli_num_rows(mysqli_query($bd, "SELECT id FROM med WHERE pri=0 AND uti='".$uti_perfil['id']."';"));
		$perfil_num_alb = mysqli_num_rows(mysqli_query($bd, "SELECT id FROM med_alb WHERE uti='".$uti_perfil['id']."';"));
		
		#INICIO - Publicações, Albúns
		echo "
		<div class='p-0 mt-4 col-xl-6 offset-xl-3'>
			<section class='text-center my-3'>";
			#Mostrar botões de Publicações e Albúns se houver Albúns
			if ($perfil_num_alb){
				echo "
				<button id='btn_ver_med' onclick='ver(\"med\")' class='btn btn-primary m-0 ps-3'><span class='badge rounded-pill mt-1 me-2 bg-dark text-light bg-opacity-50'>".$perfil_num_med."</span><i class='bi bi-play-btn'></i>"._('Publicações')."</button>
				<button id='btn_ver_alb' onclick='ver(\"alb\")' class='btn btn-light m-0 ps-3'><span class='badge rounded-pill mt-1 me-2 bg-dark text-light bg-opacity-50'>".$perfil_num_alb."</span><i class='bi bi bi-collection'></i>"._('Álbuns')."</button>
				";
			}
			echo"
			</section>
			<section id='section_med'>
				<section id='row_med' class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3'></section>
				<section class='my-4 text-center'>
					<button id='btn_carregar' onclick='carregar_med()' class='btn btn-light'>"._("Carregar mais")." <i class='bi bi-plus-lg'></i></button>
				</section>
			</section>
			<section id='section_alb' class='mx-sm-0 mx-1 mw-sm-100 mw-auto mb-3 row row-cols-1 row-cols-md-2' style='display: none;'></section>
		</div>
		";
		
		$append_med = '
		<div class="col p-1 p-sm-2">
		<div class="ratio ratio-4x3">
			<a class="text-light text-decoration-none" href="/m/\'+data.id+\'">
				<div class="bg-primary contentor_med h-100 rounded-xl d-flex" style="background-image:url(\'+data.thu+\');">
					<div class="rounded-bottom d-flex w-100 align-items-center align-self-end bg-dark bg-opacity-75 p-2">
						<span id="icon_\'+data.id+\'_tip" class="mx-1"></span>
						<span alt="\'+data.tit+\'" class="overflow-hidden">\'+data.tit_curto+\'</span>
					</div>
				</div>
			</a>
		</div>
		</div>';
		$append_alb = '
		<div class="col p-1 p-sm-2"><a class="text-decoration-none" href="/alb?id=\'+data.id+\'">
			<div class="bg-light bg-cover text-dark p-4 rounded-xl shadow d-flex justify-content-between align-items-center" style="background-image:linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.6)),url(\'+data.thu+\');">
            <text class="h5 m-0">\'+data.tit+\'</text><span class="badge rounded-pill bg-dark text-light">\'+data.num_med+\'</span></div>
		</a></div>';

		echo "
		<script>
		var depois_med;
		var albuns_carregados = false;
		function carregar_med(){
			result = api('ob_med',{'uti':'".$uti_perfil['nut']."','depois':depois_med});
			if (!result['err']){
				$.each(result, function (key, data) {
					$('#row_med').append('".trim(preg_replace('/\s\s+/', ' ', $append_med))."');
					switch (data.tip){
						case '1': tip_icon='camera-video'; tip_cor='primary'; break;
						case '2': tip_icon='soundwave'; tip_cor='rosa'; break;
						case '3': tip_icon='image'; tip_cor='ciano'; break;
					}
					$('#icon_'+data.id+'_tip').html('<i class=\"bi bi-'+tip_icon+'\"></i>').addClass('text-'+tip_cor);
					if (data.pri==1){
						$('#icon_'+data.id+'_tip').after(\"<span><i class='bi bi-lock-fill'></i></span>\");
					}
				})
				if (depois_med){
					$([document.documentElement, document.body]).animate({
						scrollTop: $('#btn_carregar').offset().top
					}, 0);
				}
				if (result.length < 6) { $('#btn_carregar').hide();
				} else { depois_med = result[result.length-1].id; }
			}
		}
		carregar_med();

		function ver(oqA){
			var classA = 'btn-primary';
			var classB = 'btn-light';
			if (oqA=='med'){
				oqB='alb';
			} else if (oqA=='alb'){
				oqB='med';
			}
			$('#section_'+oqA).show();
			$('#section_'+oqB).hide();
			$('#btn_ver_'+oqA).removeClass(classB).addClass(classA);
			$('#btn_ver_'+oqB).removeClass(classA).addClass(classB);
			if (albuns_carregados==false){
				carregar_alb();
			}
		}
		function carregar_alb(){
			result = api('ob_alb',{'uti':'".$uti_perfil['nut']."'});
			if (!result['err']){
				$.each(result, function (key, data) {
					$('#section_alb').append('".trim(preg_replace('/\s\s+/', ' ', $append_alb))."');
				})
				albuns_carregados = true;
			}
		}
		</script>
		";

	} else {
		echo "<h2 class='my-4 text-center'>O utilizador não existe</h2>";
	}
	?>
	</body>
</html>