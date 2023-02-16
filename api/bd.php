<?php
include_once('../pro/fun_var.php');#Variáveis
#Liga à base de dados
ob_start();
$bd=mysqli_connect($bd_hn,$bd_un,$bd_pw,$bd_db);
if (!$bd) {
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL."<br>";
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL."<br>";
    exit;
}
$bd->set_charset("utf8mb4");
ob_get_clean();

#Converter todos os scripts imbutidos em html
if (isset($_POST)){
    foreach ($_POST as $name => $val){
        $_POST[$name] = addslashes(htmlspecialchars($val));
    }
}
?>