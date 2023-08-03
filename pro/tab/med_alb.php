<?php
$sql = "CREATE TABLE med_alb(
id VARCHAR(6) NOT NULL PRIMARY KEY,
uti INT NOT NULL,                       #ID do utilizador
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci, #Título
thu VARCHAR(16) NOT NULL,               #ID da Thumbnail
dcr DATETIME DEFAULT CURRENT_TIMESTAMP  #Data de criação
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med_alb' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med_alb': " . $bd->error;
}
echo "<br>";
?>