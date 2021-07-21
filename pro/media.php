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

    #Pesquisa média sem albums
    if ($uti_perfil['id']==$uti['id']){
        $med_pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND tip='".$_GET['tip']."' AND alb IS NULL ORDER BY den DESC";
    } else {
        $med_pesquisa = "SELECT * FROM med WHERE uti='".$uti_perfil['id']."' AND tip='".$_GET['tip']."' AND alb IS NULL AND pri=0 ORDER BY den DESC";
    }

    switch($_GET['tip']){

        case 1:
            if ($resultado = $bd->query($med_pesquisa)) {
                
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
            if ($resultado = $bd->query($med_pesquisa)) {
                
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Áudios')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3'>";

                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$audio_tit = $campo['tit'];} else {$audio_tit = $campo['nom'];}
                    if ($campo['thu']){$audio_thu = "https://media.drena.xyz/thumb/".$campo['thu'].".jpg";} else {$audio_thu = "https://drena.pt/fpe/".base64_encode($uti_perfil['fot']);}
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
            echo "<div class='p-xl-5 p-4'><h1>"._('Imagens')."</h1></div>";

            #Álbuns de imagens
            $alb_pesquisa = "SELECT * FROM med_alb WHERE uti='".$uti_perfil['id']."' AND tip='".$_GET['tip']."' ORDER BY dcr DESC";
            if ($resultado = $bd->query($alb_pesquisa)) {
                echo "
                <div class='row row-cols-1 row-cols-md-2'>
                ";
                while ($campo = $resultado->fetch_assoc()) {
                    #Define o nome a aparecer
                    if (!$campo['tit']){$alb_tit=sprintf(_('Álbum de %s'),$uti_perfil['nut']);}else{$alb_tit=$campo['tit'];}
                    $alb_num_med = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med WHERE alb='".$campo['id']."';"));

                    echo"
                    <div class='col'><a class='text-decoration-none' href='/album?id=".base64_encode($campo['id'])."' ><div class='bg-light bg-cover text-dark h5 p-xl-5 p-4 mb-4 rounded-xl shadow d-flex justify-content-between align-items-center' style='background-image: linear-gradient(-45deg,rgba(255,255,255,0.2),rgba(255,255,255,0.8)), url(\"https://media.drena.xyz/thumb/".$campo['thu'].".jpg\");'>
                        ".$alb_tit."
                        <span class='badge rounded-pill bg-dark text-light'>".$alb_num_med."</span>
                    </div></a></div>
                    ";
                } 
                $resultado->free();
                echo "</div>";
            }

            #Imagens sem álbum
            if ($resultado = $bd->query($med_pesquisa)) {
                
                echo "
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
            $pesquisa = "SELECT * FROM pro WHERE uti='".$uti_perfil['id']."' ORDER BY dcr DESC";
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