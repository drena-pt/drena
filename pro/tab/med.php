<?php
$sql = "CREATE TABLE med(
id VARCHAR(16) NOT NULL PRIMARY KEY,
uti INT NOT NULL,                       #ID do utilizador
nom VARCHAR(255),                       #Nome do ficheiro quando carregado
tit VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,  #Título
tip INT NOT NULL,                       #Tipo (1:video;2:áudio;3:imagem) 
est INT DEFAULT 0,                      #Estado
thu VARCHAR(16),                        #ID da Thumbnail
alb INT,                                #ID do album
pri BOOLEAN NOT NULL DEFAULT 0,         #Privado (1:privado;0:público)
nmo INT DEFAULT 0,                      #Nivel de moderação
gos INT DEFAULT 0,                      #Número de gostos
den DATETIME DEFAULT CURRENT_TIMESTAMP, #Data de envio
FOREIGN KEY (uti) REFERENCES uti(id)
)";
/*
Estado
0:bom;
1:bitrate alto;
2:a ser processado;
3:comprimido a partir do (1);
4:codec não suportado;
5:convertido a partir do (4)

Nivel de moderação
0:ok;
1:moderado 1 vez;
2:moderado 2 vezes, considerado sensivel;
3:reportado 1 vez;
*/

if ($bd->query($sql) === TRUE) {
    echo "Tabela 'med' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'med': " . $bd->error;
}
echo "<br>";
?>