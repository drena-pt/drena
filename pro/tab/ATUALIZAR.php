<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}
?>