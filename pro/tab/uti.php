<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE uti(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nut VARCHAR(16) NOT NULL UNIQUE,
nco VARCHAR(255) NOT NULL,
ppa VARCHAR(255) NOT NULL,
mai INT,
fot INT,
ati BOOLEAN NOT NULL DEFAULT 1,
adm BOOLEAN NOT NULL DEFAULT 0,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>