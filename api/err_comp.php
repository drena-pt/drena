<?php
#API (ADMIN) - Erro compressão (Reseta o estado da média e apaga conversões e compressões)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

if ($uti['car']!=1){ #Apenas para Administradores
    header('HTTP/1.1 401 Unauthorized'); exit;
}

#Informações da media
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST['med']."'"));

#Se a média não existir
if (!$med){
    echo '{"err": "A média não foi encontrada."}'; exit;
}

if ($med['est']!=2){
    echo '{"err": "A média não está a ser processada."}';
}

$caminho_comp = $dir_media.'comp/'.$med['id'].'.mp4'; #Caminho comprimido
$caminho_conv = $dir_media.'conv/'.$med['id'].'.mp4'; #Caminho convertido

#Apaga se o filesize for 0 (ou seja, o ficheiro existe mas não tem nenhuma informação)
if (filesize($caminho_comp)==0){
	unlink($caminho_comp);
}
if (filesize($caminho_conv)==0){
	unlink($caminho_conv);
}

if ($bd->query("UPDATE med SET est=0 WHERE id='".$med['id']."';") === TRUE) {
	echo '{"est":"sucesso"}';
} else {
	echo '{"err": "'.$bd->error.'"}';
}

exit;
?>