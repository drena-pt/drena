<?php
$sql = "CREATE TABLE cod(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
uti INT NOT NULL,                               #ID do utilizador
cod VARCHAR(36) NOT NULL,                       #Código 
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,         #Data de criação
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'cod' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'cod': " . $bd->error;
}
echo "<br>";
?>