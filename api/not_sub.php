<?php
#API - Subscrever nas notificações
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

$subscription = $_POST['subscription'];

if ($subscription){
	#SQL - Se a subscrição já existe
	if(mysqli_num_rows(mysqli_query($bd,"SELECT * FROM not_sub WHERE uti='".$uti['id']."' AND sub='".$subscription."';")) > 0){
		echo '{"est":"Subscrição já feita"}';
	} else {
		#Regista a subscrição na base de dados
		if ($bd->query("INSERT INTO not_sub (uti, sub) VALUES ('".$uti['id']."', '".$subscription."');") === FALSE) {
			echo '{"err": "'.$bd->error.'"}';
		} else {
			echo '{"est":"sucesso"}';
		}
	}
} else {
	echo '{"err": "Subscrição inválida"}';
}
exit;
?>