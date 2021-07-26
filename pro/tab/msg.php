<?php
$funcoes['requerSessao']=0;
require 'fun.php'; #Funções

$sql = "CREATE TABLE msg(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
a_id INT NOT NULL,
b_id INT NOT NULL,
UNIQUE KEY (a_id, b_id),
UNIQUE KEY (b_id, a_id),
FOREIGN KEY (a_id) REFERENCES uti(id),
FOREIGN KEY (b_id) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'msg' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'msg': " . $bd->error;
}
echo "<br>";
?>