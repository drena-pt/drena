<?php 
require('head.php');
?>
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
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
			<?php
            $oq = $_GET['oq'];
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

            if ($resultado = $bd->query($pesquisa_uti)) {
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Utilizadores')."</h1></div><div class='row my-2'>";
                while ($campo = $resultado->fetch_assoc()) {
                    echo "<div class='col-md-2 col-4 my-3 text-center'>
                    <a class='perfil' href='/perfil?uti=".$campo['nut']."'>
                    <img class='mx-1 rounded-circle' src='fpe/".base64_encode($campo['fot'])."' width='64'><br>".mini_nut($campo['nut'])."</a>
                    </div>";
                }
                $resultado->free();
                echo "</div>";
            }

            if ($resultado = $bd->query($pesquisa_img)) {
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Imagens')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3 text-left'>";
    
                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/media?id=".$campo['id']."'>
                            <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['thu'].".jpg'>
                            <div class='texto-container'><text class='h6'>".$video_tit."</text></div>
                        </a>
                    </div>
                    ";
                }
                $resultado->free();
                echo "</div>";
            }

            if ($resultado = $bd->query($pesquisa_vid)) {
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Vídeos')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3 text-left'>";
    
                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/media?id=".$campo['id']."'>
                            <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['thu'].".jpg'>
                            <div class='texto-container'><text class='h6'>".$video_tit."</text></div>
                        </a>
                    </div>
                    ";
                }
                $resultado->free();
                echo "</div>";
            }

            if ($pesquisa_aud AND $resultado = $bd->query($pesquisa_aud)) {
                echo "
                <div class='p-xl-5 p-4'><h1>"._('Áudios')."</h1></div>
                <div class='row row-cols-2 row-cols-md-3 text-left'>";
    
                while ($campo = $resultado->fetch_assoc()) {
                    if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                    $uti_aud = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo['uti']."';"));
                    echo "
                    <div class='col mb-4 container'>
                        <a class='text-light' href='/media?id=".$campo['id']."'>
                            <img class='shadow rounded-xl w-100' src='fpe/".base64_encode($uti_aud['fot'])."'>
                            <div class='texto-container'><text class='h6'>".$video_tit."</text></div>
                        </a>
                    </div>
                    ";
                }
                $resultado->free();
                echo "</div>";
            }

            if ($resultado = $bd->query($pesquisa_pro)) {

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
        </div>
	</body>
</html>