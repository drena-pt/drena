<?php 
    require('head.php');
?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
            <?php
            echo "
            <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3 text-center'>
                <h1 class='display-3 m-5'>".strtoupper(_('Vídeos'))."</h1>
                <div class='row row-cols-2 row-cols-md-3'>
                ";
                $videos_pesquisa = "SELECT * FROM med WHERE tip=1 ORDER by den DESC";
                if ($videos_resultado = $bd->query($videos_pesquisa)) {
                    while ($campo = $videos_resultado->fetch_assoc()) {
                        if ($campo['tit']){$campo_tit = $campo['tit'];} else {$campo_tit = $campo['nom'];}
                        echo "
                        <div class='col mb-4 container'>
                            <a class='text-light' href='/media?id=".$campo['id']."'>
                                <div class='rounded-xl inset-shadow'>
                                    <img class='shadow rounded-xl w-100' src='".$url_media."thumb/".$campo['thu'].".jpg'>
                                    <div class='texto-container-bottom'><text class='h6'>".encurtarNome($campo_tit)."</text></div>
                                    </div>
                            </a>
                        </div>
                        ";
                    } 
                }
                echo "
                </div>

                <h1 class='display-3 m-5'>".strtoupper(_('Imagens'))."</h1>
                <div class='row row-cols-2 row-cols-md-3'>
                ";
                $imagens_pesquisa = "SELECT * FROM med WHERE tip=3 ORDER by den DESC";
                if ($imagens_resultado = $bd->query($imagens_pesquisa)) {
                    while ($campo = $imagens_resultado->fetch_assoc()) {
                        if ($campo['tit']){$campo_tit = $campo['tit'];} else {$campo_tit = $campo['nom'];}
                        echo "
                        <div class='col mb-4 container'>
                            <a class='text-light' href='/media?id=".$campo['id']."'>
                                <div class='rounded-xl inset-shadow'>
                                    <img class='shadow rounded-xl w-100' src='".$url_media."thumb/".$campo['thu'].".jpg'>
                                    <div class='texto-container-bottom'><text class='h6'>".encurtarNome($campo_tit)."</text></div>
                                    </div>
                            </a>
                        </div>
                        ";
                    } 
                    $resultado->free();
                }
                echo "
                </div>
            </div>";
            ?>
		</div>
	</body>
</html>