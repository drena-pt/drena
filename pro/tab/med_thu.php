<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE med_thu(
id VARCHAR(16) NOT NULL PRIMARY KEY,
med VARCHAR(16),
den DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>