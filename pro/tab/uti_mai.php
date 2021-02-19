<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE uti_mai(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
mai VARCHAR(255) NOT NULL,
cod VARCHAR(8) NOT NULL,
con BOOLEAN DEFAULT 0,
ree INT(1) DEFAULT 0,
ure DATETIME,
dco DATETIME,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>