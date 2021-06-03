<?php 
        require('head.php');
	?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
            <?php
            echo "
            <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
                ";
                $pesquisa = "SELECT * FROM med WHERE tip='2' ORDER by den DESC";
                if ($resultado = $bd->query($pesquisa)) {
                    while ($campo = $resultado->fetch_assoc()) {
                        if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                        echo "<a class='h4 text-decoration-none text-light' href='/audio?id=".$campo['id']."'>".$video_tit."</a><br>";
                    } 
                    $resultado->free();
                }
                echo "
            </div>";
            ?>
		</div>
	</body>
</html>