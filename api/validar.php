<?php
#Composer
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once(__DIR__.'/../vendor/autoload.php');
#Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
#Base de dados
require_once(__DIR__.'/bd.php');
#Token JWT
$jwt = $_SERVER['HTTP_AUTHORIZATION'];

#O token não é obrigatório mas também não foi enviado
if ($api_noauth and (!$jwt or $jwt=='undefined')){
    #Não acontece nada, pois não há um token
} else { #O token é obrigatório
    if (!$jwt or $jwt=='undefined'){
        header('HTTP/1.1 401 Unauthorized'); exit;
    }
    
    try {
        $token = JWT::decode($jwt, new Key($api_key, 'HS512'));
    } catch (Exception $e) {
        header('HTTP/1.1 401 Unauthorized'); exit;
    }

    #Verifica se é válido
    $now = new DateTimeImmutable();
    if ($token->iss !== $url_dominio ||
        $token->iat > $now->getTimestamp() ||
        $token->exp < $now->getTimestamp())
    {
        header('HTTP/1.1 401 Unauthorized'); exit;
    }
    #Utilizador
    $uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$token->sub."'"));
    #Bloqueia pedido no caso de conta inativa
    if ($uti['ati']!=1){
        echo "{'err': 'Utilizador inválido'}";
        header('HTTP/1.1 401 Unauthorized'); exit;
    }
}
?>