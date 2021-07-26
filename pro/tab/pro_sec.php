<?php
$sql = "CREATE TABLE pro_sec(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
pro INT NOT NULL,
tex MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
vis BOOLEAN NOT NULL DEFAULT 1,
ord INT,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (pro) REFERENCES pro(id)
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'pro_sec' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'pro_sec': " . $bd->error;
}
echo "<br>";
?>