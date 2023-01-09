<?php
# Funções
$funcoes['requerSessao'] = 0;
require __DIR__.'/pro/fun.php';
?>
<!doctype html>
<!-- Desenvolvido por Guilherme Albuquerque 2018/2023 -->
<html lang="<?php echo get_browser_language(); ?>">
	<head>
		<!-- Coisas básicas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" href="imagens/favicon.png"/>
		<meta property="og:site_name" content="drena"/>
		<?php if ($site_tit!='off') echo "<title>drena</title>"; ?>
		<meta name="theme-color" content="#111111"/>

		<!-- jQuery, jQuery form, JS Cookie -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/js-cookie@2.2.1/src/js.cookie.min.js"></script>
		
		<!-- DateJS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js" integrity="sha512-/n/dTQBO8lHzqqgAQvy0ukBQ0qLmGzxKhn8xKrz4cn7XJkZzy+fAtzjnOQd5w55h4k1kUC+8oIe6WmrGUYwODA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<!-- Bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
		<script>
			if(!('ontouchstart' in window)){
				$(function (){ $('[data-toggle="tooltip"]').tooltip() })
			}
		</script>
		
		<!-- AnimeJS -->
		<script src="node_modules/animejs/lib/anime.min.js"></script>

		<!-- iFrame Resizer -->
		<script src="https://cdn.jsdelivr.net/npm/iframe-resizer@latest/js/iframeResizer.min.js"></script>