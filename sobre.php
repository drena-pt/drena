<?php 
$site_tit = 'off';
require('head.php');
?>
    	<meta name="description" content="Sobre">
		<title>drena - Sobre</title>
		<style>
		.jumbotron{
			height: 50vh;
			background-image: linear-gradient(-90deg,rgba(0,0,0,0.6),rgba(0,0,0,0),rgba(0,0,0,0.6)),url("imagens/fundo3.jpg");
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
		}
		</style>
	</head>
	<body>
	<?php require('header.php'); ?>
    <div class='jumbotron bg-dark d-flex align-items-center text-center justify-content-center align-items-center'>
        <!-- <span>
			<img src="/imagens/logo.png" height="84" alt="" loading="lazy">
		</span> -->
    </div>

	<div class='bg-gradient'>
		<div class='px-xl-0 p-xl-5 p-4 my-0 col-xl-6 offset-xl-3 text-light'>

		<h3>Sobre a drena</h3>
		A <b>drena</b> é um projeto criado por Guilherme Ribeiro Albuquerque
		<br><br><br>

		<div class="mb-4">
			<span class="h5">Guilherme Albuquerque</span><br>
			<span class="opacity-75">• Desenvolvimento Web • Design • Hostpedagem</span>
		</div>

		<div class="mb-4">
			<span class="h5">João Devesa</span><br>
			<span class="opacity-75">• Desenvolvimento Mobile</span>
		</div>

		<div class="mb-4">
			<span class="h5">João Sá</span><br>
			<span class="opacity-75">• Desenvolvimento Mobile</span>
		</div>

		<div class="mb-4">
			<span class="h5">João Oliveira</span><br>
			<span class="opacity-75">• Resolução de problemas</span>
		</div>
		
		<div class="mb-4">
			<span class="h5">@deepcut</span><br>
			<span class="opacity-75">• Resolução de problemas</span>
		</div>
    </div>
	<?php require "footer.php"; ?>
	</body>
</html>