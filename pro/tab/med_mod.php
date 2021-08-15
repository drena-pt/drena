<?php
#Moderação da Média
$sql = "CREATE TABLE med_mod(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
med VARCHAR(16),                            #ID da média
niv INT NOT NULL,                           #Nivel proposto
uti INT NOT NULL,                           #ID do moderador
dre DATETIME DEFAULT CURRENT_TIMESTAMP      #Data de regiso
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med_mod' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med_mod': " . $bd->error;
}
echo "<br>";
?>