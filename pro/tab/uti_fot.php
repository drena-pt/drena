<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE uti_fot(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
nom VARCHAR(255),
ori MEDIUMBLOB,
fot MEDIUMBLOB,
den DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>