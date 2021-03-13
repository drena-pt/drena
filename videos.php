<?php 
        require('head.php');
        #if ($uti['adm']==0){ header("Location: /"); exit; }	#Sair da página se não for administrador
	?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
            <?php
            echo "
            <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
                <div class='row row-cols-3 row-cols-md-4'>
                ";
                function encurtarNome($nome){
                    if (strlen($nome)>=30){
                        return (substr($nome, 0, 28)."…");
                    } else {
                        return ($nome);
                    }
                }
                $pesquisa = "SELECT * FROM med ORDER by den DESC";
                if ($resultado = $bd->query($pesquisa)) {
                    while ($campo = $resultado->fetch_assoc()) {
                        if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                        echo "
                        <div class='col mb-4'>
                            <div class='card border-0 shadow'>
                                <a href='/video?id=".$campo['id']."'>
                                    <img src='http://media.drena.xyz/thumb/".$campo['id'].".jpg' class='card-img-top' alt='...'>
                                </a>
                                <div class='card-body bg-dark'>
                                    <p class='text-light card-text'>".encurtarNome($video_tit)."</p>
                                </div>
                            </div>
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