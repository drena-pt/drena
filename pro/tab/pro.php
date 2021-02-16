<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "CREATE TABLE pro(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
cor INT NOT NULL DEFAULT 0,
pri BOOLEAN NOT NULL DEFAULT 1,
ati BOOLEAN NOT NULL DEFAULT 1,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>