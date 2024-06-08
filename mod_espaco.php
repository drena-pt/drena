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

            $pastas = array("ori","comp","conv","img","som","fpe","thumb");
            $pastas_tam = array();
            foreach ($pastas as $index => $value) {
                $pastas_tam[$value] = folderSize($dir_media.$value);
            }
            $tam_total = array_sum($pastas_tam);

            asort($pastas_tam);

            #Espaço
            echo "
			<div class='bg-light text-dark'>
                <section class='p-xl-5 p-4'>
                    <h3 class='mb-3'><i class='h2 bi bi-hdd'></i>"._('Espaço usado')."</h3>
                    <!--<tt>?p=".$pasta."</tt>-->";

                    foreach ($pastas_tam as $pastas_nome => $tam) {
                        $percentage = round(($tam / $tam_total) * 100, 2);
                        echo "
                        <tt class='d-flex justify-content-between'>".$pastas_nome."<small>".bytesParaHumano($tam)."</small></tt>
                        <div class='progress'>
                            <div class='progress-bar bg-primary' role='progressbar' style='width: ".$percentage."%'></div>
                        </div>
                        ";
                    }
                    
                    $tipo_pasta = [];
                    $tipo_pasta['vid1'] = $pastas_tam['ori'];
                    $tipo_pasta['vid2'] = ($pastas_tam['comp']+$pastas_tam['conv']);
                    $tipo_pasta['img1'] = $pastas_tam['img'];
                    $tipo_pasta['img2'] = ($pastas_tam['fpe']+$pastas_tam['thu']);
                    $tipo_pasta['som'] = $pastas_tam['som'];
                    $tipo_pasta_total = array_sum($tipo_pasta);

                    echo "<br><tt class='d-flex justify-content-between'>"._('Total')."<small>".bytesParaHumano($tam_total)."</small></tt>";

                    echo "
                    <br>
                    <!--<small>"._("Original")."</small>-->
					<span class='me-3 text-primary'><i class='bi bi-camera-video'></i>".round(($tipo_pasta['vid1'] / $tipo_pasta_total) * 100, 2)."%</span>
                    <span class='me-3 text-ciano'><i class='bi bi-image'></i>".round(($tipo_pasta['img1'] / $tipo_pasta_total) * 100, 2)."%</span>
					<span class='me-3 text-rosa'><i class='bi bi-soundwave'></i>".round(($tipo_pasta['som'] / $tipo_pasta_total) * 100, 2)."%</span>
                    <div class='progress'>";

                    foreach ($tipo_pasta as $pastas_nome => $tam) {
                        switch ($pastas_nome) {
                            case 'vid1':
                              $progress_color = "primary";
                              break;
                            case 'vid2':
                              $progress_color = "primary bg-opacity-50";
                              break;
                            case 'img1':
                              $progress_color = 'ciano';
                              break;
                            case 'img2':
                              $progress_color = 'ciano bg-opacity-50';
                              break;
                            case 'som':
                              $progress_color = "rosa";
                              break;
                        }

                        $percentage = round(($tam / $tipo_pasta_total) * 100, 2);
                        echo "<div class='progress-bar bg-".$progress_color."' role='progressbar' style='width: ".$percentage."%'></div>";
                    }
                    echo "
                    </div>
                    <!--<small>"._("Processado")."</small>-->
					<span class='me-3 text-primary'><i class='bi bi-play-btn'></i>".round(($tipo_pasta['vid2'] / $tipo_pasta_total) * 100, 2)."%</span>
                    <span class='me-3 text-ciano'><i class='bi bi-person-video'></i>".round(($tipo_pasta['img2'] / $tipo_pasta_total) * 100, 2)."%</span>
                    ";



                    
                    /*

                    //Pastas de ficheiros carregados originais
                    $ori_pastas = [];
                    $ori_pastas['vid'] = $pastas_tam['ori'];
                    $ori_pastas['img'] = $pastas_tam['img'];
                    $ori_pastas['som'] = $pastas_tam['som'];
                    $ori_pastas_total = array_sum($ori_pastas);

                    echo "
                    <br><br><small>"._("Original")."</small><br>
					<span class='me-3 text-primary'><i class='bi bi-camera-video'></i>".round(($ori_pastas['vid'] / $ori_pastas_total) * 100, 2)."%</span>
                    <span class='me-3 text-ciano'><i class='bi bi-image'></i>".round(($ori_pastas['img'] / $ori_pastas_total) * 100, 2)."%</span>
					<span class='me-3 text-rosa'><i class='bi bi-soundwave'></i>".round(($ori_pastas['som'] / $ori_pastas_total) * 100, 2)."%</span>
                    <div class='progress'>";
                        foreach ($ori_pastas as $nom => $tam) {
                            switch ($nom) {
                                case 'vid':
                                $progress_color = "primary";
                                break;
                                case 'img':
                                $progress_color = 'ciano';
                                break;
                                case 'som':
                                $progress_color = "rosa";
                                break;
                            }
                            $percentage = round(($tam / $ori_pastas_total) * 100, 2);
                            echo "<div class='progress-bar bg-".$progress_color."' role='progressbar' style='width: ".$percentage."%'></div>";
                        }
                    echo "</div>";
                    ////////////////////////////////

                    
                    //Pastas de ficheiros processados
                    $pro_pastas = [];
                    $pro_pastas['vid'] = ($pastas_tam['comp']+$pastas_tam['conv']);
                    $pro_pastas['thu'] = $pastas_tam['thumb'];
                    $pro_pastas['fpe'] = $pastas_tam['fpe'];
                    $pro_pastas_total = array_sum($pro_pastas);

                    echo "
                    <br><small>"._("Processado")."</small><br>
					<span class='me-3 text-primary'><i class='bi bi-camera-video'></i>".round(($pro_pastas['vid'] / $pro_pastas_total) * 100, 2)."%</span>
                    <span class='me-3 text-ciano'><i class='bi bi-image'></i>".round(($pro_pastas['thu'] / $pro_pastas_total) * 100, 2)."%</span>
					<span class='me-3 text-rosa'><i class='bi bi-soundwave'></i>".round(($pro_pastas['fpe'] / $pro_pastas_total) * 100, 2)."%</span>
                    <div class='progress'>";
                        foreach ($pro_pastas as $nom => $tam) {
                            switch ($nom) {
                                case 'vid':
                                $progress_color = "primary bg-opacity-50";
                                break;
                                case 'thu':
                                $progress_color = 'ciano';
                                break;
                                case 'fpe':
                                $progress_color = "ciano bg-opacity-50";
                                break;
                            }
                            $percentage = round(($tam / $pro_pastas_total) * 100, 2);
                            echo "<div class='progress-bar bg-".$progress_color."' role='progressbar' style='width: ".$percentage."%'></div>";
                        }
                    echo "</div>";
                    ////////////////////////////////

                    */

                    echo "

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