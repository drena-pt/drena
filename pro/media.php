<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

# Funções
$funcoes['requerSessao'] = 0;
require 'fun.php';

$ac = $_GET['ac']; #Obtem ação

if ($ac=='lista'){
    $uti_perfil = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_GET["uti"]."'"));

    if (!$uti_perfil){
        echo "Erro: Utilizador não encontrado.";
        exit;
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
                        <a class='text-light' href='/media?id=".$campo['id']."'>
                            <div class='rounded-xl inset-shadow'>
                                <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['thu'].".jpg'>
                                <div class='texto-container-bottom h6'>".encurtarNome($video_tit)."</div>
                            </div>
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
                    if ($campo['tit']){$audio_tit = $campo['tit'];} else {$audio_tit = $campo['nom'];}
                    if ($campo['thu']){$audio_thu = "https://media.drena.xyz/thumb/".$campo['thu'].".jpg";} else {$audio_thu = "https://drena.xyz/fpe/".base64_encode($uti_perfil['fot']);}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/media?id=".$campo['id']."'>
                            <div class='rounded-xl inset-shadow'>
                                <img class='shadow rounded-xl w-100' src='".$audio_thu."'>
                                <div class='texto-container-bottom h6'>".encurtarNome($audio_tit)."</div>
                            </div>
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
                <div class='p-xl-5 p-4'><h1>"._('Imagens')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3'>";
    
                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$imagem_tit = $campo['tit'];} else {$imagem_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/media?id=".$campo['id']."'>
                            <div class='rounded-xl inset-shadow'>
                                <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['thu'].".jpg'>
                                <div class='texto-container-bottom h6'>".encurtarNome($imagem_tit)."</div>
                            </div>
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
                    <div class='col'><a class='text-decoration-none' href='/projeto?id=".base64_encode($campo['id'])."' ><div id='cartao_1' class='bg-".numeroParaCor($campo['cor'])." p-xl-5 p-4 mb-4 rounded-xl shadow'>
                        <h3 class='text-light'>".$pro_tit."</h3>
                    </div></a></div>
                    ";
                } 
                $resultado->free();
                
                echo "</div>";
            }
            break;
    }
} else if ($ac=='editarLista'){
    $alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$_GET["alb"]."'"));

    if ($uti AND $alb['uti']==$uti['id']){ #Se o utilizador estiver logado e for dono do álbum

        switch($alb['tip']){
            case 3:
                $pesquisa = "SELECT * FROM med WHERE uti='".$uti['id']."' AND tip='3' AND (alb IS NULL OR alb='".$alb['id']."') ORDER by den DESC";
                if ($resultado = $bd->query($pesquisa)) {
                    
                    echo "<div class='my-4 row row-cols-2 row-cols-md-3'>
                    <script>
                    function adicionar_med(med){
                        console.log(med);
						$.ajax({
							url: '/pro/med_alb.php?ac=adicionar&alb=".$alb['id']."&med='+med,
							type: 'POST',
							success: function(output){
								if (output=='adicionado'){
                                    $('#circulo_'+med).html(\"<i class='h2 bi bi-x-circle-fill text-primary'></i>\");
								} else if (output=='removido'){
                                    $('#circulo_'+med).html(\"<i class='h2 bi bi-plus-circle'></i>\");
                                } else {
                                    console.log(output);
                                }
							}
						});
                    }
                    </script>
                    ";
        
                    while ($campo = $resultado->fetch_assoc()) {
                        if ($campo['tit']){$imagem_tit = $campo['tit'];} else {$imagem_tit = $campo['nom'];}
                        echo "
                        <div class='col mb-4 container'>
                            <div role='button' class='rounded-xl inset-shadow' onclick='adicionar_med(\"".$campo['id']."\")'>
                                <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['thu'].".jpg'>
                                <div class='texto-container' id='circulo_".$campo['id']."'>";
                                if ($campo['alb']==$alb['id']){
                                    echo "<i class='h2 bi bi-x-circle-fill text-primary'></i>";
                                } else {
                                    echo "<i class='h2 bi bi-plus-circle'></i>";
                                }
                                echo "
                                </div>
                                <div class='texto-container-bottom h6'>".encurtarNome($imagem_tit)."</div>
                            </div>
                        </div>
                        ";
                    }
                    $resultado->free();
                    echo "</div>";
                }
                break;
        }

    } else {
        echo "Erro: Não tens permissão para aceder a esta página.";
    }
} else {
    echo "Erro: Ação inválida!";
}
?>