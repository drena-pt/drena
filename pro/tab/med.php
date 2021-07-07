<?php
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
est INT DEFAULT 0,
thu VARCHAR(16),
alb INT,
pri BOOLEAN NOT NULL DEFAULT 0,
gos INT DEFAULT 0,
den DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

/*
est (Estado):
0 = Vídeo bom
1 = Vídeo com bitrate alto
2 = Vídeo a ser processado
3 = Vídeo comprimido a partir do (1)
4 = Vídeo com codec não suportado
5 = Vídeo convertido a partir do (4)
*/

if ($bd->query($sql) === TRUE) {
    echo "Tabela criada com sucesso!";
} else {
    echo "Erro ao criar as tabelas: " . $bd->error;
}
?>