<?php
$sql = "CREATE TABLE rot(
id VARCHAR(36) NOT NULL PRIMARY KEY,
uti INT NOT NULL,                                                   #ID do utilizador
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,  #Título
rot LONGTEXT,                                                       #Roteiro (conteúdo)
dcr DATETIME DEFAULT CURRENT_TIMESTAMP,                             #Data de criação
dua DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, #Data da ultima atualização
FOREIGN KEY (uti) REFERENCES uti(id)
)";

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'rot' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'rot': " . $bd->error;
}
echo "<br>";
?>