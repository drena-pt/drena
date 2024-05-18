<?php
        /* error_reporting(E_ALL);
        ini_set('display_errors', 'On'); */

		require('head.php');
		if ($uti['car']==0){ header("Location: /"); exit; }	#Sair da página se não for ADMIN ou MOD
		?>
        <script src='/js/api.min.js'></script>
        <style>
        table {
            font-size: 13px;
            /* display: table-cell; */
            width: 100%;
            padding-left: 10px;
        }
        a{
            text-decoration: none!important;
        }
        </style>
	</head>
	<body>
	    <?php require('header.php'); ?>
	    <?php

        if ($_GET['p']){
            $pasta = $_GET['p'].'/';
        } else {
            $pasta = 'img/';
        }

		echo "
		<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>";

            function folderSize($dir){
                $size = 0;
                foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
                    $size += is_file($each) ? filesize($each) : folderSize($each);
                }
                return $size;
            }

            $pastas = array("img","ori","comp","conv","fpe","som","thumb");
            $pastas_tam = array();

            $tam_total = folderSize($dir_media);

            foreach ($pastas as $index => $value) {
                $pastas_tam[$value] = folderSize($dir_media.$value);
            }
            asort($pastas_tam);

            #Espaço
            echo "
			<div class='bg-light text-dark'>
                <section class='p-xl-5 p-4'>
                    <h3 class='mb-3'><i class='h2 bi bi-hdd'></i>"._('Espaço usado')."</h3>
                    <!--<tt>?p=".$pasta."</tt>-->";

                    foreach ($pastas_tam as $pastas_nome => $tam) {
                        $percentage = round(($tam / 1073741824) * 100, 2);
                        echo "
                        <tt class='d-flex justify-content-between'>".$pastas_nome."<small>".bytesParaHumano($tam)."</small></tt>
                        <div class='progress'>
                            <div class='progress-bar bg-primary' role='progressbar' style='width: ".$percentage."%'></div>
                        </div>
                        ";
                    }
                    echo "<br><tt class='d-flex justify-content-between'>"._('Total')."<small>".bytesParaHumano($tam_total)."</small></tt>

                </section>

                <div class='table p-xl-5 p-4'>
                    <table class='table table-light'>
                        <tr class='opacity-75'>
                            <th scope='col'>Ficheiro</th>
                            <th scope='col'>Tamanho</th>
                            <th scope='col'></th>
                            <th scope='col'></th>
                        </tr>
                        ";

                        $dirPath = $dir_media.$pasta;

                        $files = scandir($dirPath);
                        foreach ($files as $file) {
                            $filePath = $dirPath . '/' . $file;
                            if (is_file($filePath)) {
                                $med_id = substr($file, 0, 16);
                                $med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$med_id."';"));
                                if ($med){
                                    $med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."';"));
                                    $username = $med_uti['nut'];
                                    $estado_media = "<a class='text-verde ms-3' href='/m/".$med['id']."'>⬤</a>";
                                    $data_media = $med['den'];
                                } else {
                                    $username = null;
                                    $estado_media = "<a class='text-vermelho ms-3' href='".$url_media.$pasta.$file."'>⬤</a>";
                                    $data_media = date ("Y-m-d H:i:s", filemtime($filePath));
                                }
                                echo "
                                <tr>
                                    <td><tt>".substr($file, 0, 20)."</tt></td>
                                    <td><tt>".filesize($filePath)."</tt></td>
                                    <td><b>".bytesParaHumano(filesize($filePath))."</b></td>
                                    <td>".$estado_media."</td>
                                    <td><tt>".$data_media."</tt></td>
                                    <td><tt>".$username."</tt></td>
                                </tr>
                                ";
                            }
                        }

                        echo "
                    </table>
                </div>
			</div>

		</div>
		";
		?>

	</body>
</html>