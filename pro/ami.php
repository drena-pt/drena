<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
#Funções
require 'fun.php';
#Função de Notificações
require(__DIR__."/not.php");

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

if ($ami_uti_a['id']){ #Se o utilizador enviou o pedido
	$bd->query("DELETE FROM ami WHERE id='".$ami_uti_a['id']."'");		#Remover conhecido / Cancelar pedido
} else if ($ami_uti_b['id']){ #Se o utilizador recebeu o pedido
	if ($ami_uti_b['sim']==1 AND $ami_uti_b['nao']==0){	#Se já aceitou
		$bd->query("DELETE FROM ami WHERE id='".$ami_uti_b['id']."'");	#Remover conhecido
	} else {
		$bd->query("UPDATE ami SET sim='1', b_dat='".date("Y-m-d H:i:s")."' WHERE id='".$ami_uti_b['id']."'"); #Aceitar amizade.
		notificacao($uti['id'],$uti_b['id'],'ami_aceite');
		}
} else { #Enviar pedido de conhecido
	$bd->query("INSERT INTO ami (a_id, b_id) VALUES ('".$uti['id']."', '".$uti_b['id']."')");
	notificacao($uti['id'],$uti_b['id'],'ami_pedido');
}
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>