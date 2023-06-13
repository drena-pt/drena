<?php
# Define o tempo para Portugal
date_default_timezone_set("UTC");

# Obtem as variáveis
require_once('fun_var.php');

# Liga à base de dados
ob_start();
$bd=mysqli_connect($bd_hn,$bd_un,$bd_pw,$bd_db);
if (!$bd) {
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL."<br>";
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL."<br>";
    exit;
}
$bd->set_charset("utf8mb4");
ob_get_clean();

#Converter todos os scripts imbutidos em html
if (isset($_POST)){
    foreach ($_POST as $name => $val){
        $_POST[$name] = addslashes(htmlspecialchars($val));
    }
}

# requerSessao (Padrão: 1)
session_start();
if ($_SESSION["uti"]){
	$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));

	# Verificar se a conta está ativa
	if ($uti['ati']==0){ echo "A tua conta foi desativada por um administrador."; session_destroy(); exit; }

	$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
} else if ($funcoes['requerSessao']!=0){
	header("Location: /entrar.php");
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
putenv("LANG=$locale");
putenv("LANGUAGE=$locale");
setlocale(LC_ALL, $locale);
textdomain("messages");
bindtextdomain("messages", "locale");

# Número para cor
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

#Encurtar nome
function encurtarNome($nome, $tamanho=19){
    if (strlen($nome)>=$tamanho){
        return (mb_substr($nome, 0, $tamanho-1)."…");
    } else {
        return ($nome);
    }
}

# Bytes para Humano
function bytesParaHumano($size,$unit="") {
	if( (!$unit && $size >= 1<<30) || $unit == "GB")
		return number_format($size/(1<<30),2)."GB";
	if( (!$unit && $size >= 1<<20) || $unit == "MB")
		return number_format($size/(1<<20),2)."MB";
	if( (!$unit && $size >= 1<<10) || $unit == "KB")
		return number_format($size/(1<<10),2)."KB";
	return number_format($size)." bytes";
}
?>