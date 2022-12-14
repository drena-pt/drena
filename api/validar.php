<?php
#Composer
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('../vendor/autoload.php');
#Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
#Base de dados
include_once('bd.php');
#Token JWT
$jwt = $_SERVER['HTTP_AUTHORIZATION'];
if (!$jwt){
    header('HTTP/1.0 400 Bad Request'); exit;
}
$token = JWT::decode($jwt, new Key($api_key, 'HS512'));
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
#Verificar se a conta está ativa
if ($uti['ati']!=1){ echo "{'err': 'Utilizador inválido'}"; exit; }
?>