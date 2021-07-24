<?php
# Funções
$funcoes['notificacao']=1;
require 'fun.php';

$ac = $_GET['ac']; #Obtem ação

if ($ac=='eliminar'){ #Se a ação for eliminar o comentário

	$com = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_com WHERE id='".$_GET["id"]."';")); #Informações do comentário
	if ($com AND $com['uti']==$uti['id']){ # Se o comentário existir e o utilizador for o dono
		# Apaga o comentário da base de dados
		if ($bd->query("DELETE FROM med_com WHERE id='".$com['id']."'") === FALSE) {
            echo "Erro mysqli: ".$bd->error;
        } else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	} else {
		echo "Erro: O comentário é inválido.";
	}
} else if ($ac=='criar'){ #Se a ação criar um comentário

	$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["med"]."';")); #Informações da media
	if ($med){ #Se a media existir
		$com = $_POST['input_com'];
		if ($com){ # Se o texto de comentário não for nulo
			if ($bd->query("INSERT INTO med_com (uti, med, tex) VALUES('".$uti['id']."', '".$med["id"]."', '".addslashes($com)."');") === FALSE) {
				echo "Erro: ".$bd->error;
			} else { # Upload com sucesso!
				if ($med['uti']!=$uti['id']){
					$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med["uti"]."';")); #Informações do dono da media
					// Define o título da notificação consoante o tipo de média
					if ($med['tip']=='1'){
						$not_tit = sprintf(_('%s comentou o teu vídeo'),$uti['nut']);
					} else if ($med['tip']=='2'){
						$not_tit = sprintf(_('%s comentou o teu áudio'),$uti['nut']);
					} else if ($med['tip']=='3'){
						$not_tit = sprintf(_('%s comentou a tua imagem'),$uti['nut']);
					} else {
						$not_tit = sprintf(_('%s comentou a tua publicação'),$uti['nut']);
					}
					mandarNotificacao($uti['nut'], $uti_mai['cod'], $med_uti['nut'], $not_tit, $url_site.'fpe/'.base64_encode($uti["fot"]), $com, $url_media.'thumb/'.$med['thu'].'.jpg');
				}
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
		} else {
			echo "Erro: O comentário não pode ser nulo.";
		}
	} else {
		echo "Erro: A Media não existe.";
	}
}
exit;
?>