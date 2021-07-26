<?php
$sql = "CREATE TABLE not_sub(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    uti INT NOT NULL,
    sub MEDIUMTEXT NOT NULL,
    dcr DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'not_sub' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'not_sub': " . $bd->error;
}
echo "<br>";
?>