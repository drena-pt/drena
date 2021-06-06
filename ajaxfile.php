<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
# Conectar à base de dados
require 'pro/fun.php';

# Função para gerar um código
function gerarCodigo($length){   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}

// Count total files
$countfiles = count($_FILES['files']['name']);
// To store uploaded files path
$files_arr = array();

// Loop all files
for($index = 0;$index < $countfiles;$index++){

   if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){

      if (is_uploaded_file($_FILES['files']['tmp_name'][$index])) {
         $ficheiro_mime = mime_content_type($_FILES['files']['tmp_name'][$index]);
         $ficheiro_ext = end(explode(".", $_FILES['files']['name'][$index]));
     
         # Se o mime não tiver a string 'image/'
         if (strpos($ficheiro_mime, "image") === false) {
             $erro = "O ficheiro não é uma imagem: ".$ficheiro_mime;
             goto criarJson;
         } else {

            gerarCodigo:
            $codigo = gerarCodigo(16);
            # Verifica na base de dados se já existe esse código, se sim repete.
            if(mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$codigo."'"))){
               goto gerarCodigo;
            }

            // File path
            $path = "../media.drena.xyz/teste/".$codigo.".".$ficheiro_ext;

            // Upload file
            if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path)){

               #Regista o áudio na base de dados
               if ($bd->query("INSERT INTO med (id, uti, nom, tip) VALUES('".$codigo."', '".$uti['id']."', '".$_FILES['files']['name'][$index]."', '3');") === FALSE) {
                  $erro = "Erro mysqli:".$bd->error;
                  goto criarJson;
               }

               $files_arr[] = array("link"=>"https://drena.xyz/imagem?id=".$codigo,"img"=>"https://media.drena.xyz/teste/".$codigo.".".$ficheiro_ext,"tit"=>$_FILES['files']['name'][$index]);
            } else {
               $erro = "Não foi possivel carregar o ficheiro.";
               goto criarJson;
            }
         }
     
      } else {
            $erro = "Não foi possivel detetar o tipo de ficheiro.";
            goto criarJson;
      }
      
   }
}

echo json_encode($files_arr);
die;

criarJson:
echo $erro;
exit;
?>