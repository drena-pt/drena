<?php
# Conectar à base de dados
require_once ('../ligarbd.php');

$sql = "CREATE TABLE med_thu(
id VARCHAR(16) NOT NULL PRIMARY KEY,
med VARCHAR(16),
den DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med_thu' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med_thu': " . $bd->error;
}
echo "<br>";
?>