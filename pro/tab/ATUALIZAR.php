<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}

#Se a coluna 'nom' (Nome original do ficheiro) existir na tabela 'med'
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW COLUMNS FROM med LIKE 'nom'")))?TRUE:FALSE;
if ($sql==1){
    #Remove a antiga coluna
    if ($bd->query("ALTER TABLE med DROP COLUMN nom") === TRUE) {
        echo "<br>Removida coluna 'nom' (Nome original do ficheiro) na tabela 'med'.";
    } else {
        echo "<br>Erro mysql:".$bd->error;
    }
}

echo "<br><h1>FIM</h1>";
exit;
?>