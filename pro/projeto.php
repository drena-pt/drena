<?php /*
require 'fun.php'; #Funções
$ac = $_GET['ac']; #Ação

if ($ac=='criar'){

	$pro_vazio = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE uti='".$uti['id']."' AND tit=''"));
	if ($pro_vazio){
		$pro_vazio_sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE pro='".$pro_vazio['id']."'"));
		if (!$pro_vazio_sec){
			header("location: ../projeto?id=".base64_encode($pro_vazio['id']));
			exit;
		}
	}
	
	$cor_aleatoria = rand(0,7);

	$sql = "INSERT INTO pro (uti, tit, cor)
	VALUES ('".$uti["id"]."', '".$_POST['tit_input']."', '".$cor_aleatoria."')";

	if (mysqli_query($bd, $sql)) {
		$ultimo = mysqli_insert_id($bd);
		header("location: ../projeto?id=".base64_encode($ultimo));
	} else {
		echo "Erro: " . $sql . "<br>" . $bd->error;
	}

} else {
	
	$pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET['id'])."'"));

	if ($pro AND $pro['uti']==$uti['id']){ # Se o projeto existir e o utilizador conectado for o dono

		if ($ac=='eliminar'){
	
			if ($bd->query("DELETE FROM pro_sec WHERE pro='".$pro['id']."'") === FALSE) {
				echo "Erro: ".$bd->error;
			} else if ($bd->query("DELETE FROM pro WHERE id='".$pro['id']."'") === FALSE) {
				echo "Erro: ".$bd->error;
			} else {
				header("Location: /u/".$uti['nut']);
			}
		} else if ($ac=='cor'){
	
			function corParaNumero($cor){
				switch ($cor) {
					case 'azul': return 1; break;
					case 'verde': return 2; break;
					case 'amarelo': return 3; break;
					case 'vermelho': return 4; break;
					case 'rosa': return 5; break;
					case 'ciano': return 6; break;
					case 'primary': return 7; break;
					default: return 0;
				}
			}
		
			if ($bd->query("UPDATE pro SET cor=".corParaNumero($_GET["cor"])." WHERE id='".$pro['id']."'") === FALSE) {
				echo "Erro: ".$bd->error;
			}
		} else {
			echo "Erro: Nenhuma ação selecionada.";
		}
	} else {
		echo "Erro: Projeto inválido.";
	}
}
exit;
*/ ?>