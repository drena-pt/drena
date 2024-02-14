<?php 
$site_tit = 'off';
require('head.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On');  */

function utis($u) {
	global $bd;
	return mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut = '$u';"));
}
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

		<div class="alert border-0 bg-primary bg-opacity-10 mb-3">
			<?php
			echo "
			<a href='/u/guilhae'>
			<img class='rounded-circle me-2' src='".$url_media."fpe/".utis('guilhae')['fpe'].".jpg' width='40' height='40'></a>
			";
			?>
			<span class='h5'>Guilherme Albuquerque</span>
			<section class="text-end">
				<span class="opacity-75">• Desenvolvimento Web • Design • Hostpedagem</span>
			</section>
		</div>

		<div class="alert border-0 bg-primary bg-opacity-10 mb-3">
			<?php
			echo "
			<a href='/u/devas'>
			<img class='rounded-circle me-2' src='".$url_media."fpe/".utis('devas')['fpe'].".jpg' width='40' height='40'></a>
			";
			?>
			<span class='h5'>João Devesa</span>
			<section class="text-end">
				<span class="opacity-75">• Desenvolvimento Mobile</span>
			</section>
		</div>

		
		<div class="alert border-0 bg-primary bg-opacity-10 mb-3">
			<?php
			echo "
			<a href='/u/phi19'>
			<img class='rounded-circle me-2' src='".$url_media."fpe/".utis('phi19')['fpe'].".jpg' width='40' height='40'></a>
			";
			?>
			<span class='h5'>João Sá</span>
			<section class="text-end">
				<span class="opacity-75">• Desenvolvimento Mobile</span>
			</section>
		</div>

		<div class="alert border-0 bg-primary bg-opacity-10 mb-3">
			<?php
			echo "
			<a href='/u/fxvnder'>
			<img class='rounded-circle me-2' src='".$url_media."fpe/".utis('fxvnder')['fpe'].".jpg' width='40' height='40'></a>
			";
			?>
			<span class='h5'>João Oliveira</span>
			<section class="text-end">
				<span class="opacity-75">• Resolução de problemas</span>
			</section>
		</div>

		<div class="alert border-0 bg-primary bg-opacity-10 mb-3">
			<?php
			echo "
			<a href='/u/v'>
			<img class='rounded-circle me-2' src='".$url_media."fpe/".utis('v')['fpe'].".jpg' width='40' height='40'></a>
			";
			?>
			<span class='h5'>@deepcut</span>
			<section class="text-end">
				<span class="opacity-75">• Resolução de problemas</span>
			</section>
		</div>
		
    </div>
	<?php require "footer.php"; ?>
	</body>
</html>