<?php
require('fun.php'); #FUNÇÕES
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["med"]."';")); #Informações da media
if ($med){ #Se a media existir
	
	$com = $_POST['input_com'];

	if ($com){
		if ($bd->query("INSERT INTO med_com (uti, med, tex) VALUES('".$uti['id']."', '".$med["id"]."', '".$com."');") === FALSE) {
			echo "Erro: ".$bd->error;
		} else { # Upload com sucesso!
			//echo "true";
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	} else {
		echo "Erro: O comentário não pode ser nulo.";
	}
} else {
	echo "Erro: A Media não existe.";
}
exit;
?>