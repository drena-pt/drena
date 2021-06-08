<?php
# Define o tempo para UTC 0
date_default_timezone_set('Europe/Lisbon');

# Liga à base de dados
ob_start();
require_once ('ligarbd.php');
ob_get_clean();
session_start();

# requerSessao (Padrão: 1)
if ($_SESSION["uti"]){
	$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));
} else if ($funcoes['requerSessao']!=0){
	header("Location: ../entrar.php");
	exit;
}

# Obtem a língua
function get_browser_language($available=['pt','en','de','it','fr'],$default='en') {
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if (empty($available)) {
        return $langs[ 0 ];
        }
		foreach ( $langs as $lang ){
			$lang = substr( $lang, 0, 2 );
			if( in_array( $lang, $available ) ) {
				return $lang;
			}
		}
	}
	return $default;
}
switch (get_browser_language()) {
	case 'pt': $locale = "pt_PT.UTF-8"; break;
    case 'en': $locale = "en_GB.UTF-8"; break;
    case 'de': $locale = "de_CH.UTF-8"; break;
    case 'it': $locale = "it_IT.UTF-8"; break;
    case 'fr': $locale = "fr_CH.UTF-8"; break;
}
setlocale(LC_ALL, $locale);
bindtextdomain("messages", "locale");
textdomain("messages");
?>