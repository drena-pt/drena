<?php
	require('head.php');
	$alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".base64_decode($_GET["id"])."'"));	#Informações Álbum
	$alb_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$alb["uti"]."'"));					#Informações Utilizador dono

	#Permissão
	if ($alb_uti['id']==$uti['id']){
		$per = 1;
	}
?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<?php
		if ($alb){ #Se o álbum existir

			echo "<div class='offset-xl-3 col-xl-6'>";

			#Define o nome a aparecer
			if (!$alb['tit']){$alb_tit=sprintf(_('Álbum de %s'),$alb_uti['nut']);}else{$alb_tit=$alb['tit'];}

			echo "
			<section id='alb_header' class='p-xl-5 p-4 my-0 my-xl-4 shadow rounded-xl bg-light bg-cover text-dark' style='background-image: linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.8)), url(\"".$url_media."thumb/".$alb['thu'].".jpg\");'>
				<h1 id='alb_tit'>".$alb_tit."</h1>
			";
			if ($per){
				echo "
				<section class='text-start'>
					<button class='btn btn-dark text-light' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
					"._('Configurações')." <i class='bi bi-sliders'></i>
					</button>

					<button id='header_btn_adicionar' class='btn btn-dark text-light'>
					"._('Adicionar')." <i class='bi bi-plus-circle'></i>
					</button>
					
					<button id='header_btn_concluido' style='display: none;'' class='btn btn-dark text-light'>
					"._('Concluído')." <i class='bi bi-check-circle'></i>
					</button>

					<script>
					$('#header_btn_adicionar').click(function(){
						$('#conteudo').load('/pro/media.php?ac=editarLista&alb=".$alb['id']."');
						$('#header_btn_adicionar').hide();
						$('#header_btn_concluido').show();
					});
					$('#header_btn_concluido').click(function(){
						location.reload();
					});
					</script>
				</section>
			</section>

			<section id='collapseExample' class='mb-2 bg-dark text-light collapse shadow'>
				<div class='p-xl-5 p-4'>
					<h3>"._('Configurações')."</h3>
				
					<text class='h5'>"._('Título')."</text>
					<form class='row' method='post' action='/pro/med_alb.php?ac=titulo&alb=".$alb['id']."'>
						<div class='col-sm-6 col-auto'>
							<input type='text' class='form-control' id='alb_tit_input' name='alb_tit' placeholder=\"".sprintf(_('Álbum de %s'),$alb_uti['nut'])."\" maxlength='40' value='".$alb['tit']."'>
						</div>
						<div class='col-auto'>
							<button class='btn btn-light'>"._('Alterar')."</button>
						</div>
					</form>
					<br>

					<button class='btn btn-vermelho ml-1' data-toggle='modal' data-target='#modal_eliminar_alb'>
					"._('Eliminar álbum')." <i class='bi bi-trash'></i>
					</button>

					<script>
					$('#alb_tit_input').on('input', function() { 
						if($(this).val()){
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
							<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
							<a href='pro/med_alb.php?ac=eliminar&alb=".$alb['id']."' role='button' class='btn btn-vermelho text-light'>"._('Eliminar')."</a>
						</div>
					</div>
				</div>
			</div>
			";
			} else {
				echo "
				<div class='row mb-1'>
					<div class='col-auto pr-0 text-center'>
						<a href='/perfil?uti=".$alb_uti['nut']."'><img src='".$url_media."fpe/".$alb_uti['fpe'].".jpg' class='rounded-circle' width='40'></a>
					</div>
					<div class='col d-flex'>
						<span class='justify-content-center align-self-center'>".sprintf(_('Criado por %s'),$alb_uti['nut'])."</span>
					</div>
				</div>
			</section>
			";
			}

			#Pesquisa por média
			if ($alb['uti']==$uti['id']){
				$pesquisa = "SELECT * FROM med WHERE alb='".$alb['id']."' ORDER by den DESC";
			} else { #Oculta média privada
				$pesquisa = "SELECT * FROM med WHERE alb='".$alb['id']."' AND pri=0 ORDER by den DESC";
			}

			#Conteúdo
			echo "<section id='conteudo'>";
            if ($resultado = $bd->query($pesquisa)){
				echo "<div class='my-4 row row-cols-2 row-cols-md-3'>";
                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$imagem_tit = $campo['tit'];} else {$imagem_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 contentor'>
                        <a class='text-light' href='/media?id=".$campo['id']."'>
							<div class='rounded-xl inset-shadow'>
								<img class='rounded-xl w-100 shadow' src='".$url_media."thumb/".$campo['thu'].".jpg'>
								<div class='texto-contentor-bottom h6'>".encurtarNome($imagem_tit)."</div>
							</div>
                        </a>
                    </div>
                    ";
                }
				echo "</div>";
                $resultado->free();

				header("Location: /pro/media.php?ac=editarLista&alb=".$alb['id']);
            } else {
				echo "Álbum vazio.";
			}
			echo "</section>";

			echo "</div>";

		} else {
			echo "<h2 class='my-4 text-center'>Álbum não encontrado! ☹️</h2>";
		}
		?>
	</body>
</html>