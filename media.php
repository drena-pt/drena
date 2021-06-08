<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/

# Funções
$funcoes['requerSessao'] = 0;
require 'pro/fun.php';

function encurtarNome($nome){
    if (strlen($nome)>=48){
        return (substr($nome, 0, 46)."…");
    } else {
        return ($nome);
    }
}
function numeroParaCor($num){
	switch ($num) {
		case 1: return 'azul'; break;
		case 2: return 'verde'; break;
		case 3: return 'amarelo'; break;
		case 4: return 'vermelho'; break;
		case 5: return 'rosa'; break;
		case 6: return 'ciano'; break;
		case 7: return 'primary'; break;
		default: return 'dark';
	}
}

if ($_GET['ac']=='lista'){
    $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));

    if (!$uti_perfil){
        echo "Erro: Utilizador não encontrado.";
        exit;
    } else {
        echo "
        <style>
            .container {
                position: relative;
            }
            .container img{
                filter: brightness(75%);
            }
            .texto-container {
                text-shadow: rgb(0, 0, 0) 0px 0px 10px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        </style>
        ";
    }

    switch($_GET['tip']){


        case 1:
            $pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND tip='1' ORDER by den DESC";
            if ($resultado = $bd->query($pesquisa)) {
                
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Vídeos')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3'>";

                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/video?id=".$campo['id']."'>
                            <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['id'].".jpg'>
                            <div class='texto-container'><text class='h6'>".encurtarNome($video_tit)."</text></div>
                        </a>
                    </div>
                    ";
                } 
                $resultado->free();
                echo "</div>";
            }
            break;
        

        case 2:
            $pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND tip='2' ORDER by den DESC";
            if ($resultado = $bd->query($pesquisa)) {
                
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Áudios')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3'>";

                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/audio?id=".$campo['id']."'>
                            <img class='shadow rounded-xl w-100' src='https://drena.xyz/fpe/".base64_encode($uti_perfil['fot'])."'>
                            <div class='texto-container'><text class='h6'>".encurtarNome($video_tit)."</text></div>
                        </a>
                    </div>
                    ";
                } 
                $resultado->free();
                echo "</div>";
            }
            break;


        case 3:
            $pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND tip='3' ORDER by den DESC";
            if ($resultado = $bd->query($pesquisa)) {
                
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Vídeos')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3'>";
    
                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/imagem?id=".$campo['id']."'>
                            <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/teste/".$campo['id'].".".end(explode(".", $campo['nom']))."'>
                            <div class='texto-container'><text class='h6'>".encurtarNome($video_tit)."</text></div>
                        </a>
                    </div>
                    ";
                } 
                $resultado->free();
                echo "</div>";
            }
            break;


        default:

            $pesquisa = "SELECT * FROM pro WHERE uti='".$uti_perfil['id']."'";
            if ($resultado = $bd->query($pesquisa)) {

                echo "
                <div class='p-xl-5 p-4'><h1>"._('Projetos')."</h1></div>
                <div class='row row-cols-1 row-cols-md-2'>
                ";

                while ($campo = $resultado->fetch_assoc()) {
                    if (!$campo['tit']){$pro_tit=_('Projeto');}else{$pro_tit=$campo['tit'];}
                    echo"
                    <div class='col'><a class='text-decoration-none' href='/projeto?id=".base64_encode($campo['id'])."' ><div id='cartao_1' class='bg-".numeroParaCor($campo['cor'])." text-dark p-xl-5 p-4 mb-4 rounded-xl shadow'>
                        <h3 class='text-light'>".$pro_tit."</h3>
                    </div></a></div>
                    ";
                } 
                $resultado->free();
                
                echo "</div>";
            }
            break;
    }
}
?>