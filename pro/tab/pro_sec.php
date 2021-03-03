<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE pro_sec(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
pro INT NOT NULL,
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
tit_ati BOOLEAN NOT NULL DEFAULT 1,
tex MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
ati BOOLEAN NOT NULL DEFAULT 1,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (pro) REFERENCES pro(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>