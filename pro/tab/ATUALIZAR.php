<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require '../fun.php'; #Funções

#Se a coluna adm (Administrador) na tabela uti (Utilizadores) existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW COLUMNS FROM uti LIKE 'adm'")))?TRUE:FALSE;
if ($sql==1){
    #Adiciona a nova coluna car (Cargo) na tabela uti (Utilizadores)
    if ($bd->query("ALTER TABLE uti ADD car INT NOT NULL DEFAULT 0 AFTER adm") === TRUE) {
        echo "<br>Adicionada coluna car (Cargo) na tabela uti (Utilizadores)";
    } else {
        echo "<br>Erro ao adicionar coluna car:".$bd->error;
    }
    #Para cada registo na tabela uti (Utilizadores) pega nos administradores e passa as variaveis para a nova coluna car (Cargo)
    $sql = "SELECT * FROM uti WHERE adm=1";
    if ($resultado = $bd->query($sql)){
        while ($campo = $resultado->fetch_assoc()){
            if ($bd->query("UPDATE uti SET car=1 WHERE id=".$campo["id"].";") === TRUE) {
                echo "<br>Utilizador ".$campo['nut']." definido como administrador.";
            } else {
                echo "<br>Erro ao definir utilizador ".$campo['nut']." como administrador: ".$bd->error;
            }
        } 
    }
    #Remove a coluna adm (Administrador) da tabela uti (Utilizadores)
    if ($bd->query("ALTER TABLE uti DROP COLUMN adm") === TRUE) {
        echo "<br>Removida a coluna adm (Administrador) da tabela uti (Utilizadores)";
    } else {
        echo "<br>Erro ao remover coluna adm:".$bd->error;
    }
}

#Se a coluna nmo (Nivel de moderação) na tabela med (Média) não existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW COLUMNS FROM med LIKE 'nmo'")))?TRUE:FALSE;
if ($sql!=1){
    #Adiciona a nova coluna nmo (Nivel de moderação) na tabela med (Média)
    if ($bd->query("ALTER TABLE med ADD nmo INT DEFAULT 0 AFTER pri") === TRUE) {
        echo "<br>Adicionada coluna nmo (Nivel de moderação) na tabela med (Média)";
    } else {
        echo "<br>Erro ao adicionar coluna nmo:".$bd->error;
    }
}

#Se a for encontrado algum registo na tabela (med_mod) aka se ela existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW TABLES LIKE 'med_mod'")))?TRUE:FALSE;
if ($sql!=1){
    #Cria a nova tabela med_mod (Moderação da Média)
    require('med_mod.php'); #Média - Moderação
}

#Se a tabela (cod) não existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW TABLES LIKE 'cod'")))?TRUE:FALSE;
if ($sql!=1){
    #Cria a nova tabela cod (Códigos de acesso)
    require('cod.php');
}

#Se a tabela (rot) não existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW TABLES LIKE 'rot'")))?TRUE:FALSE;
if ($sql!=1){
    #Cria a nova tabela rot (Roteiros)
    require('rot.php');
}

/* $sql = "ALTER TABLE pro DROP COLUMN ati;";
//$sql = "ALTER TABLE mis_par ADD FOREIGN KEY (a_id) REFERENCES uti(id);";
//$sql = "ALTER TABLE uti_fot CHANGE fot ori MEDIUMBLOB;";

if ($bd->query($sql) === TRUE) {
    echo "Tabela editada com sucesso!";
} else {
    echo "Erro ao editar: " . $bd->error;
} */
?>