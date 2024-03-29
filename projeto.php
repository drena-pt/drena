<?php /*
require('head.php');
$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["id"])."'"));	#Informações Projeto
$pro_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$pro["uti"]."'"));				#Informações Utilizador do projeto
if ($pro_uti['id']==$uti['id']){ $per = 1; $vis = 1; }	#Dar premissão e visualização
if ($pro['pri']==0){ $vis = 1; }						#Dar visualização
$vis = 1;

if ($_POST['pro_tit']){
	if ($per){
		if ($bd->query("UPDATE pro SET tit='".addslashes($_POST['pro_tit'])."' WHERE id='".$pro['id']."'") === FALSE) {
			echo "Erro:".$bd->error;
			exit;
		}
		$pro['tit'] = $_POST['pro_tit'];
	}
}
?>
		<!-- EditorJS -->
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.19.3"></script>
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
		<script src="js/edjsHTML.browser.js"></script>
	</head>
	<body>
		<?php require('header.php'); ?>
		<?php
		if ($pro AND $vis){ #Se o projeto existir e for visivel.

			if (!$pro['tit']){$pro_tit=_('Projeto');}else{$pro_tit=$pro['tit'];}

			echo "
			<div class='p-xl-5 p-4 offset-xl-3 col-xl-6 text-light rounded-xl my-0 my-xl-4 shadow bg-".numeroParaCor($pro['cor'])."' id='pro_header'>
				<h1 id='pro_tit'>".$pro_tit."</h1>
			";
			
			if ($per){
			
				echo "
				<section class='text-start'>
					<button class='btn btn-light text-".numeroParaCor($pro['cor'])."' data-bs-toggle='collapse' data-bs-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample' id='header_btn'>
					"._('Configurações')." <i class='bi bi-sliders'></i>
					</button>

					<button class='btn btn-light text-".numeroParaCor($pro['cor'])."' id='header_btn_criar'>
					"._('Nova secção')." <i class='bi bi-plus-circle'></i>
					</button>

					<script>
						$('#header_btn_criar').click(function(){
						  $.ajax({
						  	url: 'pro/sec.php?ac=criar&pro=+".$_GET["id"]."',
						  	success: function(result) {
						  	  if (result){
						  	  	alert('Ocorreu um erro.');
						  	  } else {
						  	  	location.reload();
						  	  }
						  	},
						  	error: function(){
						  	  alert('Ocorreu um erro.');
						  	}
						  });
						});
					</script>
					<br>
				</section>
			</div>

			<div class='p-0 my-0 offset-xl-3 col-xl-6'>
				<section id='collapseExample' class='mb-2 bg-dark text-light collapse'>
					<div class='p-xl-5 p-4'>
						<h3>"._('Configurações')."</h3>
					
						<text class='h5'>"._('Título')."</text>
						<form class='row' method='post'>
							<div class='col-sm-6 col-auto'>
								<input type='text' class='form-control' id='pro_tit_input' name='pro_tit' placeholder=\""._('Projeto')."\" maxlength='40' value='".$pro['tit']."'>
							</div>
							<div class='col-auto'>
								<button class='btn btn-light'>"._('Alterar')."</button>
							</div>
						</form>
						<br>

						<text class='h5'>"._('Cor')."</text>
						<section>
							<button class='btn text-light btn-dark' onclick=\"pro_cor('dark')\">"._('Preto')."</button>
							<button class='btn text-light btn-azul' onclick=\"pro_cor('azul')\">"._('Azul')."</button>
							<button class='btn text-light btn-verde' onclick=\"pro_cor('verde')\">"._('Verde')."</button>
							<button class='btn text-light btn-amarelo' onclick=\"pro_cor('amarelo')\">"._('Amarelo')."</button>
							<button class='btn text-light btn-vermelho' onclick=\"pro_cor('vermelho')\">"._('Vermelho')."</button>
							<button class='btn text-light btn-rosa' onclick=\"pro_cor('rosa')\">"._('Rosa')."</button>
							<button class='btn text-light btn-ciano' onclick=\"pro_cor('ciano')\">"._('Ciano')."</button>
							<button class='btn text-light btn-primary' onclick=\"pro_cor('primary')\">"._('Roxo')."</button>
						</section>
						<br>

						<button class='btn btn-light ml-1' data-bs-toggle='modal' data-bs-target='#modal_eliminar_pro'>
							"._('Eliminar projeto')." <i class='bi bi-trash'></i>
						</button>

						<script>
						cor = '".numeroParaCor($pro['cor'])."';
						console.log('cor: '+cor);

						function pro_cor(nova_cor){
							if (nova_cor!=cor){
								//$('*[id*=sec], #criar_sec').addClass('bg-'+nova_cor);
								//$('*[id*=sec], #criar_sec').removeClass('bg-'+cor);
								$('#pro_header').addClass('bg-'+nova_cor);
								$('#pro_header').removeClass('bg-'+cor);
								$('*[id*=header_btn]').addClass('text-'+nova_cor);
								$('*[id*=header_btn]').removeClass('text-'+cor);
								cor = nova_cor;
								$.ajax({
									url: 'pro/projeto.php?ac=cor&id=".$_GET["id"]."&cor='+nova_cor,
									success: function(data){
										console.log(data);
									},
									error: function(){
										alert('Ocorreu um erro');
									}
								});
							}
						}

						$('#pro_tit_input').on('input', function() { 
							if($(this).val()){
								$('#pro_tit').text($(this).val())
							} else {
								$('#pro_tit').text('"._('Projeto')."')
							}
						});
						</script>
					</div>
				</section>

				<!-- Modal Eliminar Projeto-->
				<div class='modal fade' id='modal_eliminar_pro' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_pro_label' aria-hidden='true'>
					<div class='modal-dialog' role='document'>
						<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
							<div class='modal-header'>
								<h2 class='modal-title' id='modal_eliminar_pro_label'>"._('Eliminar projeto')."<br></h2><br>
							</div>
							<div class='modal-body'>
								<text><span class='h5'>".$pro_tit."</span><br>"._('Esta ação é irreversível!')."</text>
							</div>
							<div class='modal-footer text-end'>
								<button type='button' class='btn btn-light' data-bs-dismiss='modal'>"._('Cancelar')."</button>
								<a href='pro/projeto.php?ac=eliminar&id=".$_GET['id']."' role='button' class='btn btn-vermelho text-light'>"._('Eliminar')."</a>
							</div>
						</div>
					</div>
				</div>
			";
			} else {
				echo "
					<div class='row mb-1'>
						<div class='col-auto pr-0 text-center'>
							<a href='/u/".$pro_uti['nut']."'><img src='".$url_media."fpe/".$pro_uti['fpe'].".jpg' class='rounded-circle' width='40'></a>
						</div>
						<div class='col d-flex'>
							<span class='justify-content-center align-self-center'>".sprintf(_('Criado por %s'),$pro_uti['nut'])."</span>
						</div>
					</div>
				</div>
				<div class='p-0 my-0 offset-xl-3 col-xl-6'>
				";
			}
			#Secções
			$pesquisa = "SELECT * FROM pro_sec WHERE pro=".$pro['id']." ORDER BY ord ASC";
            if ($resultado = $bd->query($pesquisa)) {
				$num_sec = 0;
                while ($campo = $resultado->fetch_assoc()) {
					if ($per OR $campo['vis']==1){
						echo "
						<section class='bg-dark mb-2 p-xl-5 p-4' id='sec_".$num_sec."'>";

						if ($per){
							echo "
							<div class='d-flex flex-row-reverse mb-3'>
								<div>
									<button class='btn btn-light ml-1' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-original-title=\""._('Visibilidade')."\" onclick=\"visibilidade('".base64_encode($campo['id'])."',".$num_sec.")\">
										<i class='bi bi-eye'></i>
									</button>

									<button onclick=\"editarSeccao('".$campo['id']."')\" class='btn btn-light ml-1' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-original-title=\""._('Editar texto')."\">
										<i class='bi bi-pencil'></i>
									</button>

									<button class='btn btn-light ml-1' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-original-title=\""._('Eliminar')."\" onclick=\"eliminar_sec('".base64_encode($campo['id'])."',".$num_sec.")\">
										<i class='bi bi-trash'></i>
									</button>

									<div class='btn-group ml-1' role='group' aria-label='Basic example'>
										<a href='pro/sec.php?sec=".base64_encode($campo['id'])."&ac=moverBaixo' role='button' class='btn btn-light' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-original-title=\""._('Mover para baixo')."\">
											<i class='bi bi-arrow-down'></i>
										</a>
										<a href='pro/sec.php?sec=".base64_encode($campo['id'])."&ac=moverCima' role='button' class='btn btn-light' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-original-title=\""._('Mover para cima')."\">
											<i class='bi bi-arrow-up'></i>
										</a>
									</div>
								</div>


								<text class='my-auto me-auto h5 mb-3' id='tit_".$num_sec."'>
									"._('Secção')." ".$num_sec;
									if ($campo['vis']==0){echo" ("._('Invisível').")";}
									echo "
								</text>
							</div>
							";
						}
						echo "
							<style>
							.sec_tex a{
								color: var(--bs-".numeroParaCor($pro['cor']).") !important;
							}
							</style>
							<div class='sec_tex' id='sec_".$campo['id']."_tex'>
								";
								if ($campo['tex']){
									echo "
									<script>
										var edjsParser = edjsHTML();
										$('#sec_".$campo['id']."_tex').html(edjsParser.parse(".$campo['tex']."));
										console.log('".$campo['tex']."');
									</script>
									";
								}
								echo "
							</div>
						</section>
						";
						$num_sec++;
					}
                } 
				$resultado->free();
				echo "<script>$('iframe').iFrameResize();";
				if ($per){
					echo "
					function editarSeccao(sec_id){
						$('#sec_'+sec_id+'_tex').load('pro/sec_editar.php?ac=editar&sec='+sec_id, function(){ console.log('A editar a secção: '+sec_id) });
					}
					function eliminar_sec(id,num){
						$.ajax({
							url: 'pro/sec.php?sec='+id+'&ac=eliminar',
							success: function(result) {
								if (result){
									alert(result);
								} else {
									$('#sec_'+num).remove();
								}
							},
							error: function(){
								alert('Ocorreu um erro. Secção ID: '+id);
							}
						});
					}
					function visibilidade(id,num){
						$.ajax({
							url: 'pro/sec.php?sec='+id+'&ac=visibilidade',
							success: function(result) {
								if (result==='true'){
									$('#tit_'+num).text('"._('Secção')." '+num);
								} else {
									$('#tit_'+num).text('"._('Secção')." '+num+' ("._('Invisível').")');
								}
							},
							error: function(){
								alert('Ocorreu um erro. Secção ID: '+id);
							}
						});
					}";
				}
				echo "</script>";
			}
				
			echo "</div>";

		} else {
			echo "<h2 class='my-4 text-center'>Não é possivel localizar o projeto ☹️</h2>";
		}
		?>
	</body>
</html>