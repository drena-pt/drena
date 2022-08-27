<?php
$funcoes['requerSessao'] = 1;
require 'fun.php'; #Funções

if (!$_GET['url']){
    echo "{err: 'Sem url'}";
} else {
    header("location: ".$_GET['url']."/?uti=".$uti['nut']."&cod=".$uti_mai['cod']);
}

exit;
?>