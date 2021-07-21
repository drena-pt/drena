<?php 
    require('head.php');
?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
            <?php
            $sql = "SELECT * FROM ami WHERE sim=1 ORDER by b_dat DESC";
            if ($resultado = $bd->query($sql)) {
                while ($campo = $resultado->fetch_assoc()) {
                    $uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo["a_id"]."';"));
                    $uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo["b_id"]."';"));
                    echo $campo['b_dat']." - ".$uti_b['nut']." aceitou o pedido de ".$uti_a['nut']."<br>";
                } 
                $resultado->free();
            }
            ?>
		</div>
	</body>
</html>