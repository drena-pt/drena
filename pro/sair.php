<?php
#Processo - Terminar sessão
session_start();
session_destroy();
header("Location: ".$_SERVER['HTTP_REFERER']);
setcookie('drena_token', '', time()-100, '/');
exit;
?>