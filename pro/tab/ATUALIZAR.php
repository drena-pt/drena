<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

#Altera o padrão de ID's no 'med_alb' de INT para VARCHAR(6)
$bd->query("ALTER TABLE med_alb MODIFY COLUMN id INT;");
$bd->query("ALTER TABLE med_alb DROP PRIMARY KEY;");
$bd->query("ALTER TABLE med_alb MODIFY id VARCHAR(6) NOT NULL;");
$bd->query("ALTER TABLE med_alb ADD PRIMARY KEY (id);");
echo "<br>Alterada coluna 'id' na tabela 'med_alb': INT para VARCHAR(6)";

/* $bd->query("ALTER TABLE med_alb MODIFY COLUMN id VARCHAR(6);");
$bd->query("ALTER TABLE med_alb DROP PRIMARY KEY;");
$bd->query("ALTER TABLE med_alb MODIFY id INT NOT NULL;");
$bd->query("ALTER TABLE med_alb ADD PRIMARY KEY (id);"); */

$bd->query("ALTER TABLE med_alb DROP tip;");
echo "<br>Apagada coluna 'tip' na tabela 'med_alb'";

#Altera a coluna 'alb' na tabela 'med' de INT para VARCHAR(6)
if ($bd->query("ALTER TABLE med MODIFY alb VARCHAR(6);") === TRUE) {
    echo "<br>Altera coluna 'alb' na tabela 'med': INT para VARCHAR(6)";
} else {
    echo "<br>Erro mysql:".$bd->error;
}

#Para cada registo na tabela uti (Utilizadores) pega nos administradores e passa as variaveis para a nova coluna car (Cargo)
$sql = "SELECT * FROM med_alb";
if ($resultado = $bd->query($sql)){
    while ($campo = $resultado->fetch_assoc()){

        gerarCodigo:
        $codigo = gerarCodigo(6);
        #Verifica na base de dados se já existe esse código, se sim repete.
        if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$codigo."'"))){
           goto gerarCodigo;
        }

        if ($bd->query("UPDATE med SET alb='".$codigo."' WHERE alb='".$campo["id"]."';") === TRUE) {
            echo "<br>Medias do album '".$campo["id"]."' atualizadas para '".$codigo."'";
        } else {
            echo "<br>Erro: ".$bd->error;
        }

        if ($bd->query("UPDATE med_alb SET id='".$codigo."' WHERE id='".$campo["id"]."';") === TRUE) {
            echo "<br>Id do album '".$campo["id"]."' atualizado para '".$codigo."'";
        } else {
            echo "<br>Erro: ".$bd->error;
        }

    }
}


echo "<br><h1>FIM</h1>";
exit;
?>