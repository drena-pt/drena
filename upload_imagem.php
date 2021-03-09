<?php

if ($_GET['por']=='ficheiro'){
    $imagem = $_FILES['image'];
    $ficheiro_ext = end(explode(".", $imagem['name']));

    # Função para gerar um código
    function gerarCodigo($length){   
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$length; $i++) 
            $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
        return $key;
    }

    gerarCodigo:
    $codigodobem = gerarCodigo(16);

    $ficheiro_ori_caminho = "/home/root/media.drena.xyz/img/".$codigodobem.".".$ficheiro_ext;

    if (!(move_uploaded_file($imagem['tmp_name'],$ficheiro_ori_caminho))){
        echo "Não foi possivel carregar o ficheiro: ".$imagem['name'];
        exit;
    }

    echo "{\"success\":1,\"file\":{\"url\":\"https://media.drena.xyz/img/".$codigodobem.".".$ficheiro_ext."\"}}";
    exit;
} else if ($_GET['por']=='link'){
    
    $json = file_get_contents('php://input');

    echo "{ \"success\":1, \"file\":".$json."}";
    exit;
}

?>