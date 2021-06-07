<?php 
require('head.php');
?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
			<style>
			.jumbotron{
				height: 90vh;
				background-image: linear-gradient(-90deg,rgba(0,0,0,0.6),rgba(0,0,0,0),rgba(0,0,0,0.6)),url("imagens/fundo.jpg");
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
			}
			</style>
			<?php
            echo "
            <div class='jumbotron bg-dark d-flex align-items-center text-center justify-content-center align-items-center'>
                <h1 class='display-4'>".strtoupper(_("Mostra o que fazes."))."<br>
                <span class='text-outline'>";
                for ($i = 1, $j = 0; $i <= 4; $j += $i, print strtoupper(_("Mostra o que fazes."))."<br>", $i++);
                echo "</span>
                </h1>
            </div>

            <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3 text-center'>
				<h1 class='display-3 m-5'>".strtoupper(_('Ultimos vídeos'))."</h1>
                <style>
                .thumb {
                    filter: brightness(75%);
                }
                .container {
                    position: relative;
                }
                .top-left {
                    position: absolute;
                    top: 50%;
                    left: 100%;
                    transform: translate(-100%, -50%);
                }
                .centered {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                  }
                </style>
                <div class='row row-cols-2 row-cols-md-3'>
                ";
                function encurtarNome($nome){
                    if (strlen($nome)>=48){
                        return (substr($nome, 0, 46)."…");
                    } else {
                        return ($nome);
                    }
                }
                $pesquisa = "SELECT * FROM med WHERE tip='1' ORDER by den DESC LIMIT 12";
                if ($resultado = $bd->query($pesquisa)) {
                    while ($campo = $resultado->fetch_assoc()) {
                        if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                        echo "
                        <div class='col mb-4 container'>
                            <a style='text-shadow: rgb(0, 0, 0) 0px 0px 10px;' class='text-light' href='/video?id=".$campo['id']."'>
                                <img class='thumb shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['id'].".jpg'>
                                <div class='centered'><text class='h6'>".encurtarNome($video_tit)."</text></div>
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