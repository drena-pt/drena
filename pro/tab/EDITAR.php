<?php
// Conectar á base de dados
ob_start();
require_once ('../ligarbd.php');
ob_get_clean();
        
$sql = "ALTER TABLE med ADD est INT DEFAULT 0 AFTER tip;";
//$sql = "ALTER TABLE mis_par ADD FOREIGN KEY (a_id) REFERENCES uti(id);";
//$sql = "ALTER TABLE uti_fot CHANGE fot ori MEDIUMBLOB;";

if ($bd->query($sql) === TRUE) {
    echo "Tabela editada com sucesso!";
} else {
    echo "Erro ao editar: " . $bd->error;
}
?>