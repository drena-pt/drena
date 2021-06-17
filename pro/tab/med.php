﻿<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();

$sql = "CREATE TABLE med(
id VARCHAR(16) NOT NULL PRIMARY KEY,
uti INT NOT NULL,
nom VARCHAR(255),
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
tip INT NOT NULL,
thu VARCHAR(16),
gos INT DEFAULT 0,
den DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>