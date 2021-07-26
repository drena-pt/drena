<?php
$funcoes['requerSessao']=0;
require 'fun.php'; #Funções

$sql = "CREATE TABLE med_gos(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
med VARCHAR(16) NOT NULL,
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (uti) REFERENCES uti(id),
FOREIGN KEY (med) REFERENCES med(id),
UNIQUE KEY (id, uti)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med_gos' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med_gos': " . $bd->error;
}
echo "<br>";
?>