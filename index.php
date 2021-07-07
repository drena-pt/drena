<?php 
require('head.php');

# script 'notificacoes.js é o registo no sistema de notificações
if ($uti){
    $uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti["mai"]."'"));
    echo "<script>const sub_uti_nut='".$uti['nut']."';const sub_uti_cod='".$uti_mai['cod']."';</script><script src='/js/notificacoes.js'></script>";
}
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

            $index_titulos = ['pt' => "MOSTRA O QUE FAZES.",'en' => "SHOW WHAT YOU MAKE.",'de' => "ZEIG WAS DU MACHST.",'it' => "MOSTRA COSA FAI.",'fr' => "MONTREZ CE QUE VOUS FAITES."];
            unset($index_titulos[get_browser_language()]);

            echo "
            <div class='jumbotron bg-dark d-flex align-items-center text-center justify-content-center align-items-center'>
                <h1 class='display-4'>".strtoupper(_("Mostra o que fazes."))."<br>
                <span class='text-outline'>";
                foreach($index_titulos as $titulo){
                    echo $titulo."<br>";
                }
                echo "
                </span>
                </h1>

                
            </div>

            <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3 text-center'>
				<h1 class='display-3 m-5'>".strtoupper(_('Ultimos vídeos'))."</h1>
                <div class='row row-cols-2 row-cols-md-3'>
                ";
                $pesquisa = "SELECT * FROM med WHERE tip='1' AND pri=0 ORDER by den DESC LIMIT 15";
                if ($resultado = $bd->query($pesquisa)) {
                    while ($campo = $resultado->fetch_assoc()) {
                        if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                        echo "
                        <div class='col mb-4 container'>
                            <a class='text-light' href='/media?id=".$campo['id']."'>
                                <div class='rounded-xl inset-shadow'>
                                    <img class='shadow rounded-xl w-100' src='https://media.drena.xyz/thumb/".$campo['thu'].".jpg'>
                                    <div class='texto-container-bottom'><text class='h6'>".encurtarNome($video_tit)."</text></div>
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