<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();

$sql = "CREATE TABLE not_sub(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    uti INT NOT NULL,
    sub MEDIUMTEXT NOT NULL,
    dcr DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>