<?php
#API - Pedidos de amizade
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');
#Função de Notificações
require('../pro/not.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$ac = $_POST['ac']; #Ação
$uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."'"));

if (!$uti_b){
	echo '{"err": "O utilizador B não existe"}'; exit; ###############################################################EXPERIMENTAAAAAARRRR
}
if ($uti_b["nut"]==$uti['nut']){
	echo '{"err": "Não podes ser conhecido de ti mesmo"}'; exit;
}

#Utilizador conectado enviou o pedido
$ami_uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE a_id='".$uti["id"]."' AND b_id='".$uti_b["id"]."'"));
#Utilizador conectado recebeu o pedido
$ami_uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM ami WHERE a_id='".$uti_b["id"]."' AND b_id='".$uti["id"]."'"));

if ($ami_uti_a['id']){ #Se o utilizador enviou o pedido ############################################ TESTAR REMOVER O ['ID']
	$ami = $ami_uti_a;
	if ($ami_uti_a['sim']==1){
		$est = 1;
	} else {
		$est = 2;
	}
} else if ($ami_uti_b['id']){ #Se o utilizador recebeu o pedido
	$ami = $ami_uti_b;
	if ($ami_uti_b['sim']==1){	#Se já aceitou
		$est = 1;
	} else {
		$est = 3;
	}
} else { #Enviar pedido de conhecido
	$est = 0;
}

if ($ac=='ob'){ #Se a ação for apenas obter o resultado atual
	echo '{"est": "'.$est.'"}';
} else {
	switch ($est){
		case 0: #Adicionar conhecido
			$bd->query("INSERT INTO ami (a_id, b_id) VALUES ('".$uti['id']."', '".$uti_b['id']."')");
			notificacao($uti['id'],$uti_b['id'],'ami_pedido');
			$est = 2;
			break;
		case 1: #São conhecidos
		case 2: #Pedido enviado
			$bd->query("DELETE FROM ami WHERE id='".$ami['id']."'");
			$est = 0;
			break;
		case 3: #Aceitar pedido
			$bd->query("UPDATE ami SET sim='1', b_dat='".date("Y-m-d H:i:s")."' WHERE id='".$ami_uti_b['id']."'"); #Aceitar amizade.
			notificacao($uti['id'],$uti_b['id'],'ami_aceite');
			$est = 1;
			break;
	}
	echo '{"est": "'.$est.'"}';
}

exit;
?>