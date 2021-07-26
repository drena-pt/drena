<?php
$funcoes['requerSessao']=0;
require 'fun.php'; #Funções

$sql = "CREATE TABLE uti_mai(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,
mai VARCHAR(255) NOT NULL,
cod VARCHAR(8) NOT NULL,
con BOOLEAN DEFAULT 0,
ree INT(1) DEFAULT 0,
ure DATETIME,
dco DATETIME,
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'uti_mai' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'uti_mai': " . $bd->error;
}
echo "<br>";
?>