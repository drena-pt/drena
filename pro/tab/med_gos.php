<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();

$sql = "CREATE TABLE med_gos(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
med VARCHAR(16) NOT NULL,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id),
FOREIGN KEY (med) REFERENCES med(id),
UNIQUE KEY (id, uti)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>