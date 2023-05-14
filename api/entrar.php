<?php
#API / PHP APP - Entrar (Login)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
include_once('validar.php');
#JWT
use Firebase\JWT\JWT;

#Torna os inputs em variáveis
$nut = $_POST["nut"];
$ppa = $_POST["ppa"];

#Vaziu - Palavra-passe.
if (!$ppa){$erro_ppa=1;goto erros;}

#Utilizador
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$nut."'"));

if ($nut){						
	#Erro - Utilizador não encontrado ou desátivado.
	if(!$uti['id'] OR $uti['ati']==0){$erro_nut=2;goto erros;}

	if(password_verify($ppa, $uti['ppa'])){

		session_destroy();#Destroi a sessão para evitar que haja uma sessão Pre Utilizador
		session_start();
		$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));

		#Cria o Token JWT
		$jwt_date      = new DateTimeImmutable();
		$jwt_expire_at = $jwt_date->modify('+1 year')->getTimestamp();
		$jwt_data = [
			'iat'  => $jwt_date->getTimestamp(),
			'iss'  => $url_dominio,
			'exp'  => $jwt_expire_at,
			'sub'  => $uti['nut'],
		];
		$token = JWT::encode(
			$jwt_data,
			$api_key, #Obtem das variáveis
			'HS512'
		);
		setcookie('drena_token', $token, $jwt_expire_at, '/', '.'.$url_dominio);

		#Se o mail não foi confirmado:
		if ($uti_mai['con']==0){
			#Inicia sessão pre-utilizador sem mail confirmado.
			$_SESSION["pre_uti"] = $nut;
			$bd->query("UPDATE uti_mai SET ree=1 WHERE id='".$uti_mai['id']."'");
			echo '{"est":"registo"}';

		#Se o mail está confirmado:
		} else {
			#Inicia sessão do utilizador.
			$_SESSION["uti"] = $uti['nut'];
			echo '{"est":"sucesso","token":"'.$token.'"}';
		}
		exit;

	} else {
		#Erro - Palavra-passe errada.
		$erro_ppa=3;
	}
} else {
	#Se o utilizador for indefinido:
	#Vaziu - Nome de utilizador.
	$erro_nut=1;
}

#ERROS
erros:
$erros = array(
	"nut" => $erro_nut,
	"ppa" => $erro_ppa
);

#Envia um 'avi' (Aviso) porque o 'err' (Erro) faz um alert no browser.
echo '{"avi":'.json_encode($erros).'}';
exit;
?>