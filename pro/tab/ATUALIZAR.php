<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}

#Se a coluna fot (Foto de perfil antiga) existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW COLUMNS FROM uti LIKE 'fot'")))?TRUE:FALSE;
if ($sql==1){
    #Remove a antiga coluna
    if ($bd->query("ALTER TABLE uti DROP COLUMN fot") === TRUE) {
        echo "<br>Removida coluna fot (Foto de perfil) na tabela uti, para dar espaço à nova coluna fpe";
    } else {
        echo "<br>Erro mysql:".$bd->error;
    }
}

#Se a tabela uti_fot (Foto de perfil antiga) existir
if ($result = $bd->query("SHOW TABLES LIKE 'uti_fot'")) {
    if($result->num_rows == 1) {
        echo "<br>Table uti_fot existe ainda...";
        #Remove a antiga tabela
        if ($bd->query("DROP TABLE uti_fot") === TRUE) {
            echo "<br>Removida tabela uti_fot";
        } else {
            echo "<br>Erro mysql:".$bd->error;
        }
    }
}

echo "<h1>SUCESSO</h1>";
?>