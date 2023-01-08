<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}


#Se a coluna rno (Receber notificações) na tabela uti (Utilizadores) não existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW COLUMNS FROM uti LIKE 'rno'")))?TRUE:FALSE;
if ($sql!=1){
    #Adiciona a nova coluna
    if ($bd->query("ALTER TABLE uti ADD rno BOOLEAN NOT NULL DEFAULT 1 AFTER car") === TRUE) {
        echo "<br>Adicionada coluna rno (Receber notificações) na tabela uti (Utilizadores)";
    } else {
        echo "<br>Erro:".$bd->error;
    }
} else {
    echo "<br>A coluna rno (Receber notificações) já existe";
}

echo "<br><h1>FIM</h1>";
exit;
?>