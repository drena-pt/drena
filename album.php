<?php
$site_tit = 'off';
require('head.php');
#Informações Álbum
$alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$_GET["id"]."'"));

#Verifica se o albúm existe
if ($alb){
	#Informações Utilizador dono
	$alb_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$alb["uti"]."'"));
	#Define o título do albúm
	if (!$alb['tit']){$alb_tit=sprintf(_('Álbum de %s'),$alb_uti['nut']);}else{$alb_tit=$alb['tit'];}
	echo "
	<meta property='og:image' content='".$url_media."thumb/".$alb['thu'].".jpg'>
	<meta property='og:description' content='".$alb_uti['nut']."'>
	<title>".$alb_tit." - drena</title>
	";
} else {
	echo "
	<meta property='og:description' content='Álbum não encontrado'>
	<title>drena</title>";
}

#Permissão
if ($alb_uti['id']==$uti['id']){
	$uti_per = 1;
}
?>
		<script src='/js/api.min.js'></script>
	</head>
	<body>
		<?php require('header.php'); ?>
		<div class='offset-xl-3 col-xl-6'>
		<?php
		if ($alb){ #Se o álbum existir

			echo "
			<section id='alb_header' class='p-xl-5 p-4 my-2 my-xl-4 shadow rounded-xl bg-light bg-cover text-dark' style='background-image: linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.8)), url(\"".$url_media."thumb/".$alb['thu'].".jpg\");'>
				<h1 id='alb_tit'>".$alb_tit."</h1>
			";
			if ($uti_per){
				echo "
				<section class='text-start'>
					<button class='btn btn-dark text-light' data-bs-toggle='collapse' data-bs-target='#section_config' aria-expanded='false' aria-controls='section_config'>
					"._('Configurações')." <i class='bi bi-sliders'></i>
					</button>
					<button id='btn_remover' class='btn btn-dark text-light'>
					"._('Remover')." <i class='bi bi-x-circle'></i>
					</button>
					<button id='btn_adicionar' data-bs-toggle='modal' data-bs-target='#modal_med_adicionar' class='btn btn-dark text-light'>
					"._('Adicionar')." <i class='bi bi-plus-circle'></i>
					</button>

					<div id='modal_med_adicionar' class='modal fade' tabindex='-1'>
						<div class='modal-dialog modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
							<div class='modal-header mb-3'>
								<h2 class='modal-title'>Adicionar<br></h2><br>
								<button type='button' class='btn-close p-2' data-bs-dismiss='modal'></button>
							</div>
							<div class='modal-body'>
								<div class='m-0 row row-cols-3 mw-100'></div>
								<section id='row_med_adicionar' class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2'></section>
								<section class='my-4 text-center'>
									<button id='btn_carregar_mais' onclick=\"carregar_med('adicionar')\" class='btn btn-light'>Carregar mais <i class='bi bi-plus-lg'></i></button>
								</section>
							</div>
						</div>
					</div>
					
					<script>
					var ac_remover=false;
					$('#btn_remover').click(function(){
						if (ac_remover==true){
							$('#btn_remover').html(\""._('Remover')." <i class='bi bi-x-circle'></i>\");
							$('#btn_adicionar').removeClass('disabled');
							$('.efeito_remover').addClass('d-none');
							$('[id^=\"a_med_\"]').each(function () {
								$(this).attr('href', $(this).attr('link'))
								.removeAttr('link');
							});

							ac_remover=false;
						} else {
							$('#btn_remover').html(\""._('Concluído')." <i class='bi bi-check-circle'></i>\");
							$('#btn_adicionar').addClass('disabled');
							$('.efeito_remover').removeClass('d-none');
							$('[id^=\"a_med_\"]').each(function () {
								$(this).attr('link', $(this).attr('href'))
								.removeAttr('href');
							});

							ac_remover=true;
						}
					});
					</script>
				</section>
			</section>

			<section id='section_config' class='bg-dark text-light collapse shadow'>
				<div class='p-xl-5 p-4'>
					<h3>"._('Configurações')."</h3>
				
					"._('Título')."
					<form class='row' id='form_alb_tit'>
						<div class='col-sm-6 col-auto'>
							<input type='text' class='form-control' id='input_alb_tit' placeholder=\"".sprintf(_('Álbum de %s'),$alb_uti['nut'])."\" maxlength='40' value='".$alb['tit']."'>
						</div>
						<div class='col px-0'>
							<button id='btn_tit' class='disabled btn btn-light'>"._('Guardar')."</button>
						</div>
					</form>
					<br>

					<button class='btn btn-vermelho ml-1' data-bs-toggle='modal' data-bs-target='#modal_eliminar_alb'>
					"._('Eliminar álbum')." <i class='bi bi-trash'></i>
					</button>

					<script>
					$('#form_alb_tit').on('submit', function(e) {
						e.preventDefault();
						var tit = $('#input_alb_tit').val();
						result = api('med_alb',{'alb':'".$alb['id']."','ac':'tit','tit':tit});
						$('#btn_tit').addClass('disabled');
					});

					$('#input_alb_tit').on('input', function() { 
						if($(this).val()){
							$('#btn_tit').removeClass('disabled');
							$('#alb_tit').text($(this).val())
						} else {
							$('#alb_tit').text('".$alb_tit."')
						}
					});
					</script>
				</div>
			</section>

			<!-- Modal Eliminar Álbum-->
			<div class='modal fade' id='modal_eliminar_alb' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_alb_label' aria-hidden='true'>
				<div class='modal-dialog' role='document'>
					<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
						<div class='modal-header'>
							<h2 class='modal-title' id='modal_eliminar_alb_label'>"._('Eliminar álbum')."<br></h2><br>
						</div>
						<div class='modal-body'>
							<text><span class='h5'>".$alb_tit."</span><br>"._('Esta ação é irreversível!')."</text>
						</div>
						<div class='modal-footer text-end'>
							<button type='button' class='btn btn-light' data-bs-dismiss='modal'>"._('Cancelar')."</button>
							<button onclick='eliminar_alb()' class='btn btn-vermelho text-light'>"._('Eliminar')."</button>
						</div>
					</div>
				</div>
			</div>
			";
			} else {
				echo "
				<div>
					<a href='/u/".$alb_uti['nut']."'><img src='".$url_media."fpe/".$alb_uti['fpe'].".jpg' class='rounded-circle' width='40'></a>
					<span class='ms-2'>".sprintf(_('Criado por %s'),$alb_uti['nut'])."</span>
				</div>
			</section>
			";
			}

			#INICIO - Secção médias no albúm
			$append_med = '
			<div id="med_\'+data.id+\'" class="col p-1 p-sm-2">
				<div class="ratio ratio-4x3">
					<a id="a_med_\'+data.id+\'" class="text-light text-decoration-none" href="/m/\'+data.id+\'">
						<div class="bg-rosa contentor_med h-100 rounded-xl d-flex" style="background-image:url(\'+data.thu+\');">
							<div onclick="med_alb(`\'+data.id+\'`)" class="efeito_remover d-none rounded-xl d-flex align-items-center text-center position-absolute h-100 w-100 bg-dark bg-opacity-50">
								<i role="button" class="bi bi-x-circle-fill h2 text-light container h-25"></i>
							</div>
							<div class="rounded-bottom d-flex w-100 align-items-center align-self-end bg-dark bg-opacity-75 p-2">
								<span class="mx-1 text-\'+tip_cor+\'"><i class="bi bi-\'+tip_icon+\'"></i></span>
								\'+pri_icon+\'<span class="overflow-hidden">\'+data.tit_curto+\'</span>
							</div>
						</div>
					</a>
				</div>
			</div>';

			echo "
			<section id='row_med_alb' class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3'></section>
			<script>
			function gerar_med(data){
				switch (data.tip){
					case '1': tip_icon='camera-video'; tip_cor='primary'; break;
					case '2': tip_icon='soundwave'; tip_cor='rosa'; break;
					case '3': tip_icon='image'; tip_cor='ciano'; break;
				}
				var pri_icon = '';
				if (data.pri==1){
					pri_icon = \"<span><i class='bi bi-lock-fill'></i></span>\";
				}
				return('".trim(preg_replace('/\s\s+/', ' ', $append_med))."');
			}
			
			function carregar_med(sec){
				if (sec=='alb'){
					result = api('ob_med',{'alb':'".$alb['id']."'});
					meds = result.meds
				} else if (sec=='adicionar'){
					meds = api('ob_med',{'uti':'".$uti['nut']."','depois':depois_med});
				}
				
				$.each(meds, function (key, data) {
					$('#row_med_'+sec).append(gerar_med(data));
					if (sec=='adicionar'){
						$('#a_med_'+data.id).removeAttr('href').attr({
							onclick: 'med_alb(`'+data.id+'`)',
							role: 'button',
						});;
					}
				})

				if (sec=='adicionar'){
					if (meds.length < 6) { 
						$('#btn_carregar_mais').hide();
					} else { 
						$('#btn_carregar_mais').show();
						depois_med = meds[meds.length-1].id;
					}
				}
			}
			carregar_med('alb');
			";
			#Script para o dono
			if ($uti_per){
			echo "
			var depois_med;
			var lista_adicionar;
			$('#btn_adicionar').click(function(){
				if (!lista_adicionar){
					depois_med = '';
					$('#row_med_adicionar').html('');
					carregar_med('adicionar');
					lista_adicionar = true;
				}
			});

			function med_alb(med_id){
				console.log(depois_med);
				result = api('med_alb',{'ac':'med','alb':'".$alb['id']."','med':med_id});
				if (result.est=='false'){
					$('#med_'+med_id).remove();
					lista_adicionar = false;
				} else if (result.est=='true'){
					$('#med_'+med_id).prependTo('#row_med_alb');
					setTimeout(
						function() 
						{
							$('#a_med_'+med_id).attr('href', '/m/'+med_id)
							.removeAttr('onclick');
						}, 500);
				}
			}

			function eliminar_alb(){
				result = api('med_alb',{'alb': '".$alb['id']."','ac': 'eliminar'});
				if (result.est=='eliminado'){
					window.location.href = '/u/".$uti['nut']."';
				}
			}
			";
			}
			echo "
			</script>
			";
			#FIM - Secção médias no albúm

		} else {
			echo "<h2 class='my-4 text-center'>Álbum não encontrado! ☹️</h2>";
		}
		?>
		</div>
	</body>
</html>