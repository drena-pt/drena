		<?php 
		require('head.php');
		if (!$uti){
			header("Location: /entrar.php");
			exit;
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<?php
		echo "
		<div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<h1 class='p-xl-5 p-4 display-3'>".strtoupper(_('Criar'))."</h1>

			<div class='row row-cols-1 row-cols-md-2'>

				<style>
					#cartao_1, #cartao_2, #cartao_3, #cartao_4{
						position: relative;
						overflow: hidden;
					}
					#cartao_1:before, #cartao_2:before, #cartao_3:before, #cartao_4:before{
						content: '';
						width: 200%;
						height: 200%;
						position: absolute;
						top: -50%;
						left: -30%;
						z-index: 4;
						opacity: 0.4;
						background-position: center;
						background-size: 9em;
						background-repeat: no-repeat;
						transform: rotate(10deg);
					}
					#cartao_1:before{background-image: url('node_modules/bootstrap-icons/icons/blockquote-left.svg');}
					#cartao_2:before{background-image: url('node_modules/bootstrap-icons/icons/camera-reels.svg');}
					#cartao_3:before{background-image: url('node_modules/bootstrap-icons/icons/camera.svg');}
					#cartao_4:before{background-image: url('node_modules/bootstrap-icons/icons/volume-up.svg');}
				</style>

				<div class='col'><a class='text-decoration-none' href='pro/projeto.php?ac=criar'>
					<div id='cartao_1' class='bg-light text-dark p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Projeto')."</h2>
					</div></a>
				</div>

				<div class='col'><a class='text-decoration-none' href='/criar_video.php'>
					<div id='cartao_2' class='bg-primary text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Vídeo')."</h2>
					</div></a>
				</div>

				<div class='col'><a class='text-decoration-none' href='/criar_imagem.php'>
					<div id='cartao_3' class='bg-ciano text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Imagem')."</h2>
					</div></a>
				</div>

				<div class='col'><a class='text-decoration-none' href='/criar_audio.php'>
					<div id='cartao_4' class='bg-rosa text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Áudio')."</h2>
					</div></a>
				</div>

			</div>
		</div>
		";
		?>
	</body>
</html>