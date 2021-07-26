<?php
$sql = "CREATE TABLE uti_fot(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
nom VARCHAR(255),
ori MEDIUMBLOB,
fot MEDIUMBLOB,
den DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'uti_fot' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'uti_fot': " . $bd->error;
}
echo "<br>";
?>