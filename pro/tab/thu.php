<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE thu(
id VARCHAR(16) NOT NULL PRIMARY KEY,
uti INT NOT NULL,
nom VARCHAR(255),
tip INT NOT NULL,
den DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>