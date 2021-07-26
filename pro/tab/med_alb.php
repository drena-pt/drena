<?php
$sql = "CREATE TABLE med_alb(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
tip INT NOT NULL,
thu VARCHAR(16) NOT NULL,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med_alb' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med_alb': " . $bd->error;
}
echo "<br>";
?>