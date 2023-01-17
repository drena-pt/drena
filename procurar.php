<?php 
require('head.php');
$oq = $_GET['oq'];
if (!$oq){
    header("Location: /");
    exit;
}
?>
	</head>
	<body>
	<?php
    require('cabeçalho.php');

    $pesquisa_uti = "SELECT * FROM uti WHERE nut LIKE '%".$oq."%' OR nco LIKE '%".$oq."%' ORDER by id DESC;";
    $pesquisa_med = "SELECT * FROM med WHERE nom LIKE '%".$oq."%' OR tit LIKE '%".$oq."%';";
    $pesquisa_vid = "SELECT * FROM med WHERE nom LIKE '%".$oq."%' AND TIP='1' OR tit LIKE '%".$oq."%' AND TIP='1' ORDER by den DESC;";
    $pesquisa_aud = "SELECT * FROM med WHERE nom LIKE '%".$oq."%' AND TIP='2' OR tit LIKE '%".$oq."%' AND TIP='2' ORDER by den DESC;";
    $pesquisa_img = "SELECT * FROM med WHERE nom LIKE '%".$oq."%' AND TIP='3' OR tit LIKE '%".$oq."%' AND TIP='3' ORDER by den DESC;";
    $pesquisa_pro = "SELECT * FROM pro WHERE tit LIKE '%".$oq."%' ORDER by id DESC";

    $num_uti = $bd->query($pesquisa_uti)->num_rows;
    $num_med = $bd->query($pesquisa_med)->num_rows;
    $num_pro = $bd->query($pesquisa_pro)->num_rows;

    $num_total = ($num_uti+$num_med+$num_pro);

    echo "
    <div class='bg-primary bg-gradient d-flex align-items-center text-center justify-content-center p-5'>
		<h1 class='display-3'>".$num_total." resultados:<span class='h2'><br>\"".$oq."\"</span></h1>
    </div>
    
    <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
    ";
    
    function mini_nut($nut){
		if (strlen($nut)>=12){
			return (substr($nut, 0, 10)."…");
		} else {
			return ($nut);
		}
	}

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_uti))) {
        echo "
        <div class='p-xl-5 p-4'><h1>"._('Utilizadores')."</h1></div><div class='row my-2'>";
        while ($campo = $resultado->fetch_assoc()) {
            echo "<div class='col-md-2 col-4 my-3 text-center'>
            <a class='perfil' href='/perfil?uti=".$campo['nut']."'>
            <img class='mx-1 rounded-circle' src='".$url_media."fpe/".$campo['fpe'].".jpg' width='64'><br>".mini_nut($campo['nut'])."</a>
            </div>";
        }
        $resultado->free();
        echo "</div>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_img))) {
        echo "
        <div class='p-xl-5 p-4'><h1>"._('Imagens')."</h1></div>
        <div class='row row-cols-2 row-cols-md-3 text-left'>";

        while ($campo = $resultado->fetch_assoc()) {
            if ($campo['tit']){$imagem_tit = $campo['tit'];} else {$imagem_tit = $campo['nom'];}
            echo "
            <div class='col mb-4 contentor'>
                <a class='text-light' href='/media?id=".$campo['id']."'>
                    <div class='rounded-xl inset-shadow'>
                        <img class='shadow rounded-xl w-100' src='".$url_media."thumb/".$campo['thu'].".jpg'>
                        <div class='texto-contentor-bottom h6'>".encurtarNome($imagem_tit)."</div>
                    </div>
                </a>
            </div>
            ";
        }
        $resultado->free();
        echo "</div>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_vid))) {
        echo "
        <div class='p-xl-5 p-4'><h1>"._('Vídeos')."</h1></div>
        <div class='row row-cols-2 row-cols-md-3 text-left'>";

        while ($campo = $resultado->fetch_assoc()) {
            if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
            echo "
            <div class='col mb-4 contentor'>
                <a class='text-light' href='/media?id=".$campo['id']."'>
                    <div class='rounded-xl inset-shadow'>
                        <img class='shadow rounded-xl w-100' src='".$url_media."thumb/".$campo['thu'].".jpg'>
                        <div class='texto-contentor-bottom h6'>".encurtarNome($video_tit)."</div>
                    </div>
                </a>
            </div>
            ";
        } 
        $resultado->free();
        echo "</div>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_aud))) {
        echo "
        <div class='p-xl-5 p-4'><h1>"._('Áudios')."</h1></div>
        <div class='row row-cols-2 row-cols-md-3 text-left'>";

        while ($campo = $resultado->fetch_assoc()) {
            if ($campo['tit']){$audio_tit = $campo['tit'];} else {$audio_tit = $campo['nom'];}
            $uti_aud = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo['uti']."';"));
            if ($campo['thu']){$audio_thu = $url_media."thumb/".$campo['thu'].".jpg";} else {$audio_thu = $url_media."fpe/".$uti_aud['fpe'].".jpg";}
            echo "
            <div class='col mb-4 contentor'>
                <a class='text-light' href='/media?id=".$campo['id']."'>
                    <div class='rounded-xl inset-shadow'>
                        <img class='shadow rounded-xl w-100' src='".$audio_thu."'>
                        <div class='texto-contentor-bottom h6'>".encurtarNome($audio_tit)."</div>
                    </div>
                </a>
            </div>
            ";
        } 
        $resultado->free();
        echo "</div>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_pro))) {

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

    echo "</div>";
	?>
	</body>
</html>