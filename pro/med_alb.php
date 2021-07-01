<?php
require 'fun.php'; #Obter funções
$ac = $_GET['ac']; #Ação
#Redirecionar
if ($_GET['redirect']==1){
	$redirect = 1;
}

$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["med"]."';")); #Informações da média
$alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$_GET["alb"]."';")); #Informações do álbum

if ($med['uti']==$uti['id']){ #Se o utilizador for dono da média


	if ($ac=='criar'){ #Criar Albúm
		#Cria um novo álbum baseado no tipo de média
		$sql = "INSERT INTO med_alb (uti, tip, thu) VALUES ('".$uti["id"]."', '".$med['tip']."', '".$med['thu']."')";
		if (mysqli_query($bd, $sql)){
			#Obtem o registo feito na base de dados
			$med_alb_id = mysqli_insert_id($bd);

			#Adiciona a média ao album
			$sql = "UPDATE med SET alb='".$med_alb_id."' WHERE id='".$med["id"]."';";
			if (mysqli_query($bd, $sql)){
				header("location: /../album?id=".base64_encode($med_alb_id));
			} else {
				echo "Erro mysqli: ".$sql."<br>".$bd->error;
			}
		} else {
			echo "Erro mysqli: ".$sql."<br>".$bd->error;
		}


	} else if ($ac=='adicionar'){ #Adiciona/Remove média de um Albúm
		if ($alb['uti']==$uti['id']){ #Se o utilizador for dono do álbum

			if ($med['alb']==$alb['id']){ #Se a média já estiver no álbum
				#Remove a média do álbum
				$sql = "UPDATE med SET alb=NULL WHERE id='".$med["id"]."';";
				if (mysqli_query($bd, $sql)){
					echo "removido";
					if ($redirect){
						header("location: /../album?id=".base64_encode($alb['id']));
					}
				} else {
					echo "Erro mysqli: ".$sql."<br>".$bd->error;
				}
			} else {
				#Adiciona a média ao álbum
				$sql = "UPDATE med SET alb='".$alb['id']."' WHERE id='".$med["id"]."';";
				if (mysqli_query($bd, $sql)){
					echo "adicionado";
					if ($redirect){
						header("location: /../album?id=".base64_encode($alb['id']));
					}
				} else {
					echo "Erro mysqli: ".$sql."<br>".$bd->error;
				}
			}

		} else {
			echo "Erro: Álbum inválido.";
		}
	} else {
		echo "Erro: Ação inválida.";
	}

} else if ($alb['uti']==$uti['id']){ #Se o utilizador for dono do álbum

	if ($ac=='titulo'){ #Alterar título do álbum

		if ($_POST['alb_tit']){
			if ($bd->query("UPDATE med_alb SET tit='".$_POST['alb_tit']."' WHERE id='".$alb['id']."'") === FALSE) {
				echo "Erro mysqli: ".$bd->error;
				exit;
			}
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;

	} else {
		echo "Erro: Ação inválida.";
	}

} else {
	echo "Erro: Média e Álbum inválidos.";
}
exit;
?>