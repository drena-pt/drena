<?php
#API - Obter comentários (de médias)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$output = array();

#Carregar comentários
if ($_POST["med"]){

    #Informações da média
    $med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST["med"]."'"));
    if (!$med){
        echo '{"err": "Média não encontrada."}';
        header('HTTP/1.0 404 Not Found'); exit;
    }

    #SQL: Procura todos os comentários da média
    if ($resultado = $bd->query("SELECT * FROM med_com WHERE med='".$med['id']."'")) {
        while ($com = $resultado->fetch_assoc()) {
            
            $com_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$com["uti"]."'"));
            $com['uti'] = $com_uti['nut'];
            $com['uti_fpe'] = $url_media.'fpe/'.$com_uti['fpe'].'.jpg';
            $output[] = $com;
        }
    }

#Pedido inválido
} else {
    header('HTTP/1.1 400 Bad Request'); exit;
}

#Renderiza o output em json
echo json_encode($output);

?>