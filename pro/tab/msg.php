﻿<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();

$sql = "CREATE TABLE msg(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
a_id INT NOT NULL,
b_id INT NOT NULL,
UNIQUE KEY (a_id, b_id),
UNIQUE KEY (b_id, a_id),
FOREIGN KEY (a_id) REFERENCES uti(id),
FOREIGN KEY (b_id) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>