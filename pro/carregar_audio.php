<?php
# Conectar à base de dados
require 'fun.php';

$ficheiro = $_FILES['input_audio'];
$ficheiro_ext = end(explode(".", $ficheiro['name']));

if (is_uploaded_file($ficheiro['tmp_name'])) {
    $ficheiro_mime = mime_content_type($ficheiro['tmp_name']);

    # Se o mime tiver a string 'audio/'
    if (strpos($ficheiro_mime, "audio") === false) {
        # Se o utilizador for administrador, mostra mensagem com o mime
        $erro = "O ficheiro não é um áudio. ".$ficheiro_mime;
        goto criarJson;
    }

} else {
    $erro = "Não foi possivel detetar o tipo de ficheiro.";
    goto criarJson;
}

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

gerarCodigo:
$codigo = gerarCodigo(16);
# Verifica na base de dados se já existe esse código, se sim repete.
if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$codigo."'"))){
    goto gerarCodigo;
}

$caminho = "/home/guilha/www/media.drena.xyz/som/";
$ficheiro_ori_caminho = $caminho.''.$codigo.'.'.$ficheiro_ext;

if (!(move_uploaded_file($ficheiro['tmp_name'],$ficheiro_ori_caminho))){
    $erro = "Não foi possivel carregar o ficheiro: ".$ficheiro['name'];
    goto criarJson;
}

#Regista o áudio na base de dados
if ($bd->query("INSERT INTO med (id, uti, nom, tit, tip) VALUES('".$codigo."', '".$uti['id']."', '".$ficheiro['name']."', '".substr($ficheiro['name'],0,strrpos($ficheiro['name'],'.'))."', '2');") === FALSE) {
    $erro = "Erro mysqli:".$bd->error;
    goto criarJson;
}

criarJson:
$json = array("erro"=>$erro, "codigo"=>$codigo, "ext"=>$ficheiro_ext);
echo json_encode($json);
exit;
?>