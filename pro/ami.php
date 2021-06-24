<?php
# Funções
$funcoes['notificacao']=1;
require 'fun.php';

if ($_GET["uti"]==$uti['nut']){
	echo "Erro. O utilizador pedido é o utilizador conectado.";
	exit;
}
$uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));
if (!$uti_b){
	echo "Erro. O utilizador B (uti) não existe.";
	exit;
}
#Utilizador conectado enviou o pedido
$ami_uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE a_id='".$uti["id"]."' AND b_id='".$uti_b["id"]."'"));
#Utilizador conectado recebeu o pedido
$ami_uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE a_id='".$uti_b["id"]."' AND b_id='".$uti["id"]."'"));

if ($ami_uti_a['id']){ #Utilizador conectado enviou o pedido
	$bd->query("DELETE FROM ami WHERE id='".$ami_uti_a['id']."'");		#Cancelar / remover amizade
} else if ($ami_uti_b['id']){ #Utilizador conectado recebeu o pedido
	if ($ami_uti_b['sim']==1 AND $ami_uti_b['nao']==0){	#Se já aceitou
		$bd->query("DELETE FROM ami WHERE id='".$ami_uti_b['id']."'");	#Remover amizade.
	} else {
		$bd->query("UPDATE ami SET sim='1', b_dat='".date("Y-m-d H:i:s")."' WHERE id='".$ami_uti_b['id']."'"); #Aceitar amizade.
		mandarNotificacao($uti['nut'], $uti_mai['cod'], $uti_b['nut'], 'Pedido aceite', 'https://drena.xyz/fpe/'.base64_encode($uti["fot"]), $uti['nut'].' agora é teu conhecido' , null);
	}
} else { #Enviar pedido de conhecido
	$bd->query("INSERT INTO ami (a_id, b_id) VALUES ('".$uti['id']."', '".$uti_b['id']."')");
	mandarNotificacao($uti['nut'], $uti_mai['cod'], $uti_b['nut'], 'Pedido de '.$uti['nut'], 'https://drena.xyz/fpe/'.base64_encode($uti["fot"]), $uti['nut'].' quer ser teu conhecido' , null);
}
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>