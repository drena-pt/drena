<?php
$sql = "CREATE TABLE med_com(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
med VARCHAR(16) NOT NULL,
com INT,
tex TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med_com' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med_com': " . $bd->error;
}
echo "<br>";
?>