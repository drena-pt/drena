<?php
# Conectar à base de dados
require('fun.php');

$ficheiro = $_FILES['myfile'];
$ficheiro_ext = end(explode(".", $ficheiro['name']));

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

gerarCodigo:
$codigodobem = gerarCodigo(16);
if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$codigodobem."'"))){
    goto gerarCodigo;
}

$ficheiro_ori_caminho = "/home/root/media.drena.xyz/ori/".$codigodobem.".".$ficheiro_ext;

if (!(move_uploaded_file($ficheiro['tmp_name'],$ficheiro_ori_caminho))){
    $erro = "Não foi possivel carregar o ficheiro: ".$ficheiro['name'];
    goto criarJson;
}

if ($bd->query("INSERT INTO med (id, uti, nom, tip) VALUES('".$codigodobem."', '".$uti['id']."', '".$ficheiro['name']."', '1');") === FALSE) {
    $erro = "Erro mysqli:".$bd->error;
    goto criarJson;
}

criarJson:
$json = array("erro"=>$erro, "codigo"=>$codigodobem, "ext"=>$ficheiro_ext);
echo json_encode($json);
exit;
?>