<?php
#FPE - Fotos de Perfil
$sql = "CREATE TABLE uti_fpe(
id VARCHAR(8) NOT NULL PRIMARY KEY,
uti INT NOT NULL,
den DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id)
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'uti_fpe' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'uti_fpe': " . $bd->error;
}
echo "<br>";
?>