<?php
#API - Subscrever nas notificações
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

$ac = $_POST['ac']; #Ação
$ob = $_POST['ob']; #Obter
$sub = $_POST['sub']; #Subscrição

if ($ac=='receber'){

	#Ativar ou desativar (Receber Notificações)
	if ($uti['rno']==1){
		if ($bd->query("UPDATE uti SET rno=0 WHERE id='".$uti["id"]."'") === FALSE) {
            echo '{"err": "'.$bd->error.'"}';
		} else {
			echo '{"est":"false"}';
		}
	} else {
		if ($bd->query("UPDATE uti SET rno=1 WHERE id='".$uti["id"]."'") === FALSE) {
            echo '{"err": "'.$bd->error.'"}';
		} else {
			echo '{"est":"true"}';
		}
	}

} else if ($ob=='subscrever' AND $sub){ #Obtem se está ou não subscrito

	#Informações da subscrição
	$not_sub = mysqli_fetch_assoc(mysqli_query($bd,"SELECT * FROM not_sub WHERE uti='".$uti['id']."' AND sub='".$sub."';"));
	if ($not_sub['id']){
		echo '{"est":"true"}';
	} else {
		echo '{"est":"false"}';
	}

} else if ($ac=='subscrever' AND $sub){ #Subscreve ou dessubscreve
	#SQL - Se a subscrição já existe
	if(mysqli_num_rows(mysqli_query($bd,"SELECT * FROM not_sub WHERE uti='".$uti['id']."' AND sub='".$sub."';")) > 0){
		if ($bd->query("DELETE FROM not_sub WHERE uti='".$uti['id']."' AND sub='".$sub."';") === FALSE) {
            echo '{"err": "'.$bd->error.'"}';
		} else {
			echo '{"est":"false"}';
		}
	} else {
		#Regista a subscrição na base de dados
		if ($bd->query("INSERT INTO not_sub (uti, sub) VALUES ('".$uti['id']."', '".$sub."');") === FALSE) {
			echo '{"err": "'.$bd->error.'"}';
		} else {
			echo '{"est":"true"}';
		}
	}
} else {
	echo '{"err": "Ação inválida"}';
}
exit;
?>