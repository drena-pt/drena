<?php
require 'fun.php'; #Funções
if ($uti['car']!=2){ #Expulsa o utilizaor caso não seja moderador
	header("Location: ../entrar");
	exit;
}

#Informações da media
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["med"]."';"));

if ($med){ #Se a media existir

	#Registos da ultima moderação feita pelo utilizador:
	#Nivel 0 (Reverter ação)
	$med_mod_uti0 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='0';"));
    #Nivel 1 (Inapropriado)
	$med_mod_uti1 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='1';"));
    #Nivel 2 (Inaceitavel)
	$med_mod_uti2 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='2';"));
    
	#Registo da ultima moderação feita
	$med_mod_ultima = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' ORDER BY dre DESC;"));
    
	#Número de vezes em que uma mídia foi definida como inapropriada
	$med_mod_1 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND niv='1';"));
    
	$ac = $_GET['ac']; #Ação
	if ($ac==0 AND !$med_mod_uti0){ #Caso seja 0 (Reverter a ação tomada) e o moderador nunca tenha tomado essa ação.

		if ($med['nmo']==1){ #Se o nível for 1 reverte para 0
			$bd->query("UPDATE med SET nmo='0' WHERE id='".$med["id"]."';");
		} else if ($med['nmo']==2){ #Se o nível for 2 reverte para 1
			$bd->query("UPDATE med SET nmo='1' WHERE id='".$med["id"]."';");
		} else if ($med['nmo']==3){ #Se o nível for 3
			if ($med_mod_1>=2){ #reverte para 2
				$bd->query("UPDATE med SET nmo='2' WHERE id='".$med["id"]."';");
			} else { #reverte para 0
				$bd->query("UPDATE med SET nmo='0' WHERE id='".$med["id"]."';");
			}
		} else if ($med['nmo']==4){ #Se o nível for 4 reverte para 3
			$bd->query("UPDATE med SET nmo='3' WHERE id='".$med["id"]."';");
		} else {
			$ac_erro = 1;
		}

	} else if ($ac==1 AND !$med_mod_uti1){ #Caso seja 1 (Definir como sensivel) e o moderador nunca tenha tomado essa ação.

		if ($med['nmo']==0){ #Se o nível for 0 define para 1
			$bd->query("UPDATE med SET nmo='1' WHERE id='".$med["id"]."';");
		} else if ($med['nmo']==1){ #Se o nível for 1 define para 2
			$bd->query("UPDATE med SET nmo='2' WHERE id='".$med["id"]."';");
		} else {
			$ac_erro = 1;
		}

	} else if ($ac==2 AND !$med_mod_uti2){ #Caso seja 2 (Definir como inaceitavel) e o moderador nunca tenha tomado essa ação.

		if ($med['nmo']==0 OR $med['nmo']==1 OR $med['nmo']==2){ #Se o nível for 0, 1 ou 2 define para 3
			$bd->query("UPDATE med SET nmo='3' WHERE id='".$med["id"]."';");
		} else if ($med['nmo']==3){ #Se o nível for 3 define para 4
			$bd->query("UPDATE med SET nmo='4' WHERE id='".$med["id"]."';");
		} else if ($med['nmo']==4){ #Se o nível for 4 rip
			header("Location: med.php?ac=eliminar&id=".$med['id']);
			exit;
		} else {
			$ac_erro = 1;
		}

	} else {
		$ac_erro = 1;
	}

	if ($ac_erro){ #Se houver erro não regista o evento
		echo "Erro: Não foi possivel executar a ação (".$ac.").";
		exit;
	} else {
		$bd->query("INSERT INTO med_mod (med, niv, uti) VALUES ('".$med['id']."', '".$ac."', '".$uti['id']."')");
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}

} else {
	echo "Erro: A Media não existe.";
}
exit;
?>