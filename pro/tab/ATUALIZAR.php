<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
require '../fun.php'; #Funções

if ($uti['car']!=1){ #Necessita premissões de Administrador
    header('HTTP/1.1 401 Unauthorized'); exit;
}

#Cria no diretório de medias a pasta 'fpe' caso não exista
$diretorio_fpe = $dir_media."fpe/";
if (!file_exists($diretorio_fpe)) {
    echo "
    Pasta para fotos de perfil não existe! 'dir_media/fpe'<br>
    Execute:<br>
    <tt>
    sudo mkdir ".$diretorio_fpe."<br>
    sudo chown www-data:www-data ".$diretorio_fpe."
    </tt>
    ";
    exit;
} else {
    echo "A pasta 'dir_media/fpe' está criada! A continuar...<br>";
}

#Cria a nova tabela 'uti_fpe' que vai substituir a antiga 'uti_fot'
if ($result = $bd->query("SHOW TABLES LIKE 'uti_fpe'")) {
    if($result->num_rows == 1) {
        echo "A tabela uti_fpe já existe<br>";
    } else {
        echo "A tabela uti_fpe ainda não existe<br>";
        require('uti_fpe.php');
    }
}

#Se a coluna fpe (Foto de perfil) na tabela uti não existir
$sql = (mysqli_num_rows(mysqli_query($bd, "SHOW COLUMNS FROM uti LIKE 'fpe'")))?TRUE:FALSE;
if ($sql!=1){
    #Adiciona a nova coluna fpe
    if ($bd->query("ALTER TABLE uti ADD fpe VARCHAR(8) DEFAULT 'padrao' AFTER mai") === TRUE) {
        echo "<br>Adicionada coluna fpe (Foto de perfil) na tabela uti";
    } else {
        echo "<br>Erro mysql:".$bd->error;
    }
} else {
    echo "<br>Coluna fpe (Foto de perfil) já existe na tabela uti";
}

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}


#Para cada resultado da tabela, converte a fotos de utilizador em blob para ficheiro e cria um novo id.
if ($resultado = $bd->query("SELECT * FROM uti_fot")) {
    while ($campo = $resultado->fetch_assoc()) {

        #Gera código unico para media
        gerarCodigo:
        $codigo = gerarCodigo(8);
        #Verifica na base de dados se já existe esse código, se sim repete.
        if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_fpe WHERE id='".$codigo."'"))){
            goto gerarCodigo;
        }

        #Guarda na tabela nova
        if ($bd->query("INSERT INTO uti_fpe (id, uti, den) VALUES('".$codigo."', '".$campo['uti']."', '".$campo['den']."')") === FALSE) {
            echo "Error:".$bd->error;
            exit;
        }

        #Altera o id de fpe para cada utilizador
        $uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo['uti']."'"));
        
        #Se a foto que está a ser processada agora for a que o utilizador está a usar, altera.
        if ($uti['fot']==$campo['id']){
            if ($bd->query("UPDATE uti SET fpe='".$codigo."' WHERE id='".$campo['uti']."'") === FALSE) {
                echo "Error:".$bd->error;
            }
        }

        #Cria a foto em .jpg
        $myfile = fopen($diretorio_fpe.$codigo.".jpg", "w") or die("Unable to open file!");
        fwrite($myfile, $campo['fot']);
        fclose($myfile);
    } 
    $resultado->free();
}

echo "
Copia a foto padrao.jpg<br>
<tt>
sudo cp ".$dir_site."imagens/padrao.jpg ".$diretorio_fpe."padrao.jpg
</tt>
<br><br>
<h2>SUCESSO!</h2>
<h4>NÃO VOLTES A EXECUTAR ESTE FICHEIRO.</h4>";
?>