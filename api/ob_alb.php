<?php
#API - Obter médias (das páginas de prefil dos utilizadores)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
$api_noauth=true; #Não é obrigatório autenticação
require_once('validar.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

$output = array();

#Informações do utilizador
$uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_POST["uti"]."';"));
if (!$uti_perfil){
    echo '{"err": "Utilizador não encontrado."}';
    header('HTTP/1.1 400 Bad Request'); exit;
}

$alb_pesquisa = "SELECT id,tit,thu FROM med_alb WHERE uti='".$uti_perfil['id']."' ORDER BY dcr;";

if ($resultado = $bd->query($alb_pesquisa)) {
    while ($alb = $resultado->fetch_assoc()) {
		$alb['num_med'] = mysqli_num_rows(mysqli_query($bd, "SELECT id FROM med WHERE alb='".$alb['id']."';"));
		$alb['id'] = base64_encode($alb['id']);
        $alb['thu'] = $url_media.'thumb/'.$alb['thu'].'.jpg'; #Coloca o url completo, em vez de apenas o id
        $output[] = $alb;
    }
}

#Renderiza o output em json
echo json_encode($output);
?>