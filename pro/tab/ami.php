<?php
$funcoes['requerSessao']=0;
require 'fun.php'; #Funções

$sql = "CREATE TABLE ami(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
a_id INT NOT NULL,
b_id INT NOT NULL,
sim BOOLEAN DEFAULT 0, #Aceitar amizade
a_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
b_dat DATETIME,
UNIQUE KEY (a_id, b_id),
UNIQUE KEY (b_id, a_id),
FOREIGN KEY (a_id) REFERENCES uti(id),
FOREIGN KEY (b_id) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'ami' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'ami': " . $bd->error;
}
echo "<br>";
?>