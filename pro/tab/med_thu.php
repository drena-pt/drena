<?php
$funcoes['requerSessao']=0;
require 'fun.php'; #Funções

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