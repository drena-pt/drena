<?php
#Processo - Terminar sessão
include_once('fun_var.php');#Variáveis
session_start();
session_destroy();
header("Location: ".$_SERVER['HTTP_REFERER']);
setcookie('drena_token', '', time()-100, '/', '.'.$url_dominio);
exit;
?>