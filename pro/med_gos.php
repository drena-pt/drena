<?php
# Funções
$funcoes['notificacao']=1;
require 'fun.php';

$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["med"]."';"));											#Informações da media
if ($med){	#Se a media existir
	$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$med["id"]."' AND uti='".$uti['id']."';"));	#Informações do gosto
    
	# Definir título da média (para a notificação)
    if ($med['tit']){$med_tit = $med['tit'];} else {$med_tit = $med['nom'];}

	if ($med_gos){
		$bd->query("DELETE FROM med_gos WHERE med='".$med["id"]."' AND uti='".$uti['id']."';");
		if ($bd->query("UPDATE med SET gos=gos-1 WHERE id='".$med["id"]."'") === FALSE) {
			echo "Erro:".$bd->error;
		} else { # Sucesso (Tirar gosto)
			echo "false";
		}
	} else {
		$bd->query("INSERT INTO med_gos (uti, med) VALUES('".$uti['id']."', '".$med["id"]."');");
		if ($bd->query("UPDATE med SET gos=gos+1 WHERE id='".$med["id"]."'") === FALSE) {
			echo "Erro:".$bd->error;
			exit;
		} else { # Sucesso (Por gosto)
			if ($med['uti']!=$uti['id']){
				$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med["uti"]."';")); #Informações do dono da media
				// Define o título da notificação consoante o tipo de média
				if ($med['tip']=='1'){
					$not_tit = sprintf(_('%s gostou do teu vídeo'),$uti['nut']);
				} else if ($med['tip']=='2'){
					$not_tit = sprintf(_('%s gostou do teu áudio'),$uti['nut']);
				} else if ($med['tip']=='3'){
					$not_tit = sprintf(_('%s gostou da tua imagem'),$uti['nut']);
				} else {
					$not_tit = sprintf(_('%s gostou da tua publicação'),$uti['nut']);
				}
				mandarNotificacao($uti['nut'], $uti_mai['cod'], $med_uti['nut'], $not_tit, $url_site.'fpe/'.base64_encode($uti["fot"]), $med_tit, $url_media.'thumb/'.$med['thu'].'.jpg');
			}
			echo "true";
		}
	}
} else {
	echo "Erro: A Media não existe.";
}
exit;
?>