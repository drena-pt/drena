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
			<h1 class='py-xl-5 py-4 px-xl-0 px-3'>"._('Criar')."</h1>

			<div class='row row-cols-1 row-cols-md-2'>

				<style>
					#cartao_1, #cartao_2, #cartao_3, #cartao_4, #cartao_5{
						position: relative;
						overflow: hidden;
					}
					#cartao_1:before, #cartao_2:before, #cartao_3:before, #cartao_4:before, #cartao_5:before{
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
					#cartao_1:before{background-image: url('https://icons.getbootstrap.com/assets/icons/blockquote-left.svg');}
					#cartao_2:before{background-image: url('https://icons.getbootstrap.com/assets/icons/camera-reels.svg');}
					#cartao_3:before{background-image: url('https://icons.getbootstrap.com/assets/icons/camera.svg');}
					#cartao_4:before{background-image: url('https://icons.getbootstrap.com/assets/icons/volume-up.svg');}
					#cartao_5:before{background-image: url('https://icons.getbootstrap.com/assets/icons/file-earmark-text.svg');}
				</style>

				<!--<div class='col'><a class='text-decoration-none' href='pro/projeto.php?ac=criar'>
					<div id='cartao_1' class='bg-light text-dark p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Projeto')."</h2>
					</div></a>
				</div>-->

				<div class='col'><a class='text-decoration-none' href='/criar_video'>
					<div id='cartao_2' class='bg-primary text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Vídeo')."</h2>
					</div></a>
				</div>

				<div class='col'><a class='text-decoration-none' href='/criar_imagem'>
					<div id='cartao_3' class='bg-ciano text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Imagem')."</h2>
					</div></a>
				</div>

				<div class='col'><a class='text-decoration-none' href='/criar_audio'>
					<div id='cartao_4' class='bg-rosa text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Áudio')."</h2>
					</div></a>
				</div>

				<div class='col opacity-50'>
					<div id='cartao_1' class='bg-light text-dark p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2>"._('Projeto')."</h2>
					</div>
				</div>


				<!--<div class='col'><a class='text-decoration-none' href='/escritura'>
					<div id='cartao_5' class='bg-amarelo text-light p-xl-5 p-4 mb-4 rounded-xl shadow'>
						<h2 class='mb-0'>"._('Roteiro')."</h2>
						<span class='badge rounded-pill bg-white text-dark'>Beta</span>
					</div></a>
				</div>-->
			</div>
		</div>
		";
		?>
	</body>
</html>