<?php
#API - Médias (Gostos)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');
#Função de Notificações
require('../pro/not.php');

#Informações da media
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST["med"]."';"));

if ($med){ #Se a media existir
	#Informações do gosto
	$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$med["id"]."' AND uti='".$uti['id']."';"));
	#Informações do utilizador dono da média
	#$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med["uti"]."';"));

	if ($med_gos){
		$bd->query("DELETE FROM med_gos WHERE med='".$med["id"]."' AND uti='".$uti['id']."';");
		if ($bd->query("UPDATE med SET gos=gos-1 WHERE id='".$med["id"]."'") === FALSE) {
            echo '{"err": "'.$bd->error.'"}'; exit;
		} else {
			echo '{"gos":"false"}'; exit;
		}
	} else {
		$bd->query("INSERT INTO med_gos (uti, med) VALUES('".$uti['id']."', '".$med["id"]."');");
		if ($bd->query("UPDATE med SET gos=gos+1 WHERE id='".$med["id"]."'") === FALSE) {
            echo '{"err": "'.$bd->error.'"}'; exit;
		} else {
			notificacao($uti['id'],$med['uti'],'gos',$med['id']);
			echo '{"gos":"true"}'; exit;
		}
	}
} else {
	echo '{"err": "A Media não existe."}';
}
exit;
?>