<?php
ob_start();
require_once('pro/ligarbd.php');
ob_get_clean();
session_start();
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));
if ($uti AND $uti['ati']==0){ echo "A tua conta foi desativada por um administrador."; session_destroy(); exit; } #Verificar se a conta está ativa

function numeroParaCor($num){
	switch ($num) {
		case 1: return 'azul'; break;
		case 2: return 'verde'; break;
		case 3: return 'amarelo'; break;
		case 4: return 'vermelho'; break;
		case 5: return 'rosa'; break;
		case 6: return 'ciano'; break;
		case 7: return 'primary'; break;
		default: return 'dark';
	}
}
?>
<!doctype html>
<!-- Desenvolvido por Guilherme Albuquerque 2018/2021 -->
<html lang="pt">
	<head>
		<!-- Coisas básicas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" href="imagens/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="css/estilo.css">
		<meta name="description" content="Website de partilha de projetos, vídeo, música e imagens. Partilha o teu trabalho livremente na drena.">
		<title>drena</title>

		<!-- jQuery, jQuery form -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
		
		<!-- swup
		<script defer src="./node_modules/swup/dist/swup.min.js"></script>
		<script defer src="./node_modules/@swup/head-plugin/dist/SwupHeadPlugin.min.js"></script>
		<script defer src="./node_modules/@swup/scripts-plugin/dist/SwupScriptsPlugin.min.js"></script>
		<script defer src="swup.js"></script>
		 -->
		 
		<!-- Bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/css/bootstrap-icons.css">
		<script>
			$(function (){ $('[data-toggle="tooltip"]').tooltip() })
		</script>

		<!-- EditorJS -->
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.19.3"></script>
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
		<script src="js/edjsHTML.browser.js"></script>
		
		<!-- AnimeJS -->
		<script src="node_modules/animejs/lib/anime.min.js"></script>
		