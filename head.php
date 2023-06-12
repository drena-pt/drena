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
		<link rel="icon" type="image/png" href="/imagens/favicon.png"/>
		<meta property="og:site_name" content="drena"/>
		<?php if ($site_tit!='off') echo "<title>drena</title>"; ?>
		<meta name="theme-color" content="#111111"/>

		<!-- jQuery, jQuery form, JS Cookie -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/js-cookie@2.2.1/src/js.cookie.min.js"></script>

		<!-- DayJS -->
		<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/pt.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/utc.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/timezone.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
		<script>
			dayjs.extend(window.dayjs_plugin_utc);
			dayjs.extend(window.dayjs_plugin_timezone);
			dayjs.extend(window.dayjs_plugin_relativeTime);
			dayjs.locale('<?php echo get_browser_language(); ?>');
		</script>

		<!-- Bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
		<script>
			if(!('ontouchstart' in window)){
				$(function (){ $('[data-bs-toggle="tooltip"]').tooltip() })
			}
		</script>

		<!-- iFrame Resizer -->
		<script src="https://cdn.jsdelivr.net/npm/iframe-resizer@latest/js/iframeResizer.min.js"></script>