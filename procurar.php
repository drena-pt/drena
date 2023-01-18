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
    $pesquisa_med = "SELECT * FROM med WHERE tit LIKE '%".$oq."%' AND pri=0;";
    $pesquisa_vid = "SELECT * FROM med WHERE tit LIKE '%".$oq."%' AND pri=0 AND tip='1' ORDER by den DESC;";
    $pesquisa_aud = "SELECT * FROM med WHERE tit LIKE '%".$oq."%' AND pri=0 AND tip='2' ORDER by den DESC;";
    $pesquisa_img = "SELECT * FROM med WHERE tit LIKE '%".$oq."%' AND pri=0 AND tip='3' ORDER by den DESC;";
    $pesquisa_pro = "SELECT * FROM pro WHERE tit LIKE '%".$oq."%' ORDER by id DESC";

    $num_uti = $bd->query($pesquisa_uti)->num_rows;
    $num_med = $bd->query($pesquisa_med)->num_rows;
    $num_pro = $bd->query($pesquisa_pro)->num_rows;

    $num_total = ($num_uti+$num_med+$num_pro);
    
    #Função para gerar blocos de média
    function blocoMedia($tip,$id,$thu,$tit){
        global $url_media;
        switch ($tip) {
            case '1': $tip_icon='camera-video'; $tip_cor='primary'; break;
            case '2': $tip_icon='soundwave'; $tip_cor='rosa'; break;
            case '3': $tip_icon='image'; $tip_cor='ciano'; break;
        }
        return "
        <div class='col p-1 p-sm-2'>
        <a class='text-light ratio ratio-4x3 text-decoration-none' href='/media?id=".$id."'>
            <div class='bg-rosa contentor_med h-100 rounded-xl d-flex' style='background-image:url(".$url_media."thumb/".$thu.".jpg);'>
                <div class='rounded-bottom d-flex w-100 align-items-center align-self-end bg-dark bg-opacity-75 p-2'>
                    <span class='mx-1 text-".$tip_cor."'><i class='bi bi-".$tip_icon."'></i></span>
                    <span class='overflow-hidden'>".encurtarNome($tit)."</span>
                </div>
            </div>
        </a>
        </div>";
    }

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
        <h2 class='pt-xl-5 pt-4 px-xl-0 px-3'>"._('Utilizadores')."</h2>
        <section class='row m-0 mw-100'>";
        while ($campo = $resultado->fetch_assoc()) {
            echo "<div class='col-md-2 col-4 my-3 text-center'>
            <a class='perfil' href='/perfil?uti=".$campo['nut']."'>
            <img class='mx-1 rounded-circle' src='".$url_media."fpe/".$campo['fpe'].".jpg' width='64'><br>".mini_nut($campo['nut'])."</a>
            </div>";
        }
        $resultado->free();
        echo "</section>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_img))) {
        echo "
        <h2 class='pt-xl-5 pt-4 px-xl-0 px-3'>"._('Imagens')."</h2>
        <section class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3'>";
        while ($campo = $resultado->fetch_assoc()) {
            echo blocoMedia($campo['tip'],$campo['id'],$campo['thu'],$campo['tit']);
        }
        $resultado->free();
        echo "</section>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_vid))) {
        echo "
        <h2 class='pt-xl-5 pt-4 px-xl-0 px-3'>"._('Vídeos')."</h2>
        <section class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3'>";
        while ($campo = $resultado->fetch_assoc()) {
            echo blocoMedia($campo['tip'],$campo['id'],$campo['thu'],$campo['tit']);
        }
        $resultado->free();
        echo "</section>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_aud))) {
        echo "
        <h2 class='pt-xl-5 pt-4 px-xl-0 px-3'>"._('Áudios')."</h2>
        <section class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3'>";
        while ($campo = $resultado->fetch_assoc()) {
            echo blocoMedia($campo['tip'],$campo['id'],$campo['thu'],$campo['tit']);
        }
        $resultado->free();
        echo "</section>";
    }

    if (mysqli_num_rows($resultado = $bd->query($pesquisa_pro))) {

        echo "
        <h2 class='pt-xl-5 pt-4 px-xl-0 px-3'>"._('Projetos')."</h2>
        <section class='mw-100 row row-cols-1 row-cols-md-2'>
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

    echo "</section>";
	?>
	</body>
</html>