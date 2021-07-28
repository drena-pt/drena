<?php
$sql = "CREATE TABLE uti(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nut VARCHAR(16) NOT NULL UNIQUE,        #Nome de utilizador
nco VARCHAR(255) NOT NULL,              #Nome completo
ppa VARCHAR(255) NOT NULL,              #Palavra-passe
mai INT,                                #ID do email
fot INT,                                #ID da foto de perfil
ati BOOLEAN NOT NULL DEFAULT 1,         #Ativo (1:ativo;0:desativado)
car INT NOT NULL DEFAULT 0,             #Cargo (0:utilizador;1:administrador;2:moderador)
dcr DATETIME DEFAULT CURRENT_TIMESTAMP  #Data de criação
)";
if ($bd->query($sql) === TRUE) {
    echo "Tabela 'uti' criada com sucesso!";
} else {
    echo "Erro ao criar tabela 'uti': " . $bd->error;
}
echo "<br>";
?>