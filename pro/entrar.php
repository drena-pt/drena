<?php
ob_start();
require_once ('ligarbd.php');
ob_get_clean();

# Torna os posts em variáveis
$nut = $_POST["nut"];
$ppa = $_POST["ppa"];

if (!$ppa){$erro_ppa=1;}																		# Vaziu - Palavra-passe.

$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"));			# Vai buscar as informações do utilizador pedido à base de dados.

if ($nut){																						# Se o utilizador for definido:
	if(!$uti['id'] OR $uti['ati']==0){$erro_nut=2;goto erros;}									# Erro - Utilizador não encontrado ou desátivado. (Vai para o processo dos erros)
	if (!$ppa){$erro_ppa=1;goto erros;}															# Vaziu - Palavra-passe. (Vai para o processo dos erros)
	if(password_verify($ppa, $uti['ppa'])){														# Se a palavra-passe está correta:
		session_start();																		# Começa a sessão.
		$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
		if ($uti_mai['con']==0){																# Se o mail não foi confirmado:
			$_SESSION["pre_uti"] = $nut;														# Inicia sessão pre-utilizador sem mail confirmado.
			$bd->query("UPDATE uti_mai SET ree=1 WHERE id='".$uti_mai['id']."'");
			header("Location: ../registo");
			exit;
		}																						# Se o mail está confirmado:
		$_SESSION["uti"] = $uti['nut'];															# Inicia sessão do utilizador.
		header("Location: ../");
		exit;
	} else {
		$erro_ppa=3;																			# Erro - Palavra-passe errada.
	}
} else {																						# Se o utilizador for indefinido:
	$erro_nut=1;																				# Vaziu - Nome de utilizador.
}

erros:
$erros = array(																					# Se houver erros, volta para a página com um cookie dos erros.
	"nut" => $erro_nut,
	"ppa" => $erro_ppa
);
setcookie('erros', serialize($erros), time() + (4), "/");
header("location: ../entrar");
exit;
?>