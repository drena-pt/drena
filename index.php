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
				background-image: linear-gradient(-90deg,rgba(0,0,0,0.6),rgba(0,0,0,0.3),rgba(0,0,0,0.6)),url("imagens/fundo3.jpg");
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
			}
			</style>
            <div class="jumbotron bg-dark d-flex align-items-center text-center justify-content-center align-items-center">
                <h1 class="display-3 text-outline">MOSTRA O QUE FAZES.<br>
				<!--<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#play'/></svg>--></h1>
            </div>
			<?php
            echo "
            <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3 text-center'>
				<h1 class='display-3 m-5'>ULTIMOS VÍDEOS</h1>
                <div class='row row-cols-3 row-cols-md-4'>
                ";
                function encurtarNome($nome){
                    if (strlen($nome)>=30){
                        return (substr($nome, 0, 28)."…");
                    } else {
                        return ($nome);
                    }
                }
                $pesquisa = "SELECT * FROM med ORDER by den DESC LIMIT 12";
                if ($resultado = $bd->query($pesquisa)) {
                    while ($campo = $resultado->fetch_assoc()) {
                        echo "
                        <div class='col mb-4'>
                            <div class='card border-0 shadow'>
                                <a href='/video?id=".$campo['id']."'>
                                    <img src='http://media.drena.xyz/thumb/".$campo['id'].".jpg' class='card-img-top' alt='...'>
                                </a>
                                <div class='card-body bg-dark text-left'>
                                    <p class='text-light card-text'>".encurtarNome($campo['nom'])."</p>
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