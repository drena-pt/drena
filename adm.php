<?php
        /*  error_reporting(E_ALL);
        ini_set('display_errors', 'On');  */

		require('head.php');
		if ($uti['car']!=1){ header("Location: /"); exit; }	#Sair da página se não for administrador
		?>
        <script src='/js/api.min.js'></script>
        <style>
        table {
            font-size: 13px;
        }
        </style>
	</head>
	<body>
	    <?php require('header.php'); ?>
	    <?php

        $num_uti = mysqli_num_rows(mysqli_query($bd, "SELECT id FROM uti;"));
        $num_med = mysqli_num_rows(mysqli_query($bd, "SELECT id FROM med;"));
        function folderSize($f){
            $io = popen ( '/usr/bin/du -sh ' . $f, 'r' );
            $size = fgets ( $io, 4096);
            $size = substr ( $size, 0, strpos ( $size, "\t" ) );
            pclose ( $io );
            return $size;
        }
        $tam_media = folderSize($dir_media);

		echo "
		<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>

			<div class='p-xl-5 p-4 bg-dark text-light'>
				<h2>"._('Ferramentas de Administrador')."</h2>
                <a href='/mod_espaco' class='btn btn-light'><i class='bi bi-hdd'></i>"._("Espaço usado")."</a>

                <div class='row row-cols-3 mt-4'>
                    <div class='col text-center'>
                        <div class='h5 mb-2 m-auto d-flex align-items-center justify-content-center rounded-circle bg-opacity-10 border bg-ciano border-ciano' style='height: 110px; width: 110px'>
                        <i class='me-2 bi bi-people'></i>".$num_uti."
                        </div>
                        "._("Utilizadores")."
                    </div>
                    <div class='col text-center'>
                        <div class='h5 mb-2 m-auto d-flex align-items-center justify-content-center rounded-circle bg-opacity-10 border bg-primary border-primary' style='height: 110px; width: 110px'>
                        <i class='me-2 bi bi-play-btn'></i>".$num_med."
                        </div>
                        "._("Médias")."
                    </div>
                    <div class='col text-center'>
                        <div class='h5 mb-2 m-auto d-flex align-items-center justify-content-center rounded-circle bg-opacity-10 border bg-rosa border-rosa' style='height: 110px; width: 110px'>
                        <i class='me-2 bi bi-hdd'></i>".$tam_media."
                        </div>
                        "._("Espaço usado")."
                    </div>
                </div>
			</div>
            ";

            #Número de media em estado 2 (A comprimir)
            $num_med_est_2 = mysqli_num_rows(mysqli_query($bd, "SELECT id FROM med WHERE est=2;"));

            #Erros de compressão
            if ($num_med_est_2!=0){
            echo "
            <div class='bg-light text-dark'>

                <div class='d-md-flex p-xl-5 p-4'>
                    <div>
                        <h4>A comprimir</h4>
                    </div>
                </div>

                <div class='table-responsive px-2'>
                    <table class='table table-light'>
                        <tr>
                        <th scope='col'>Thumb</th>
                        <th scope='col'>Título</th>
                        <th scope='col'>U</th>
                        <th scope='col'>Tam. ori</th>
                        <th scope='col'>Tam. comp</th>
                        <th scope='col'>Tam. conv</th> 
                        </tr>
                        ";

                        if ($resultado = $bd->query("SELECT * FROM med WHERE est=2;")) {
                            while ($med = $resultado->fetch_assoc()){

                                $med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id=".$med['uti'].";"));

                                $caminho_ori = glob($dir_media.'ori/'.$med['id'].'.*')[0];  #Caminho original
                                $caminho_comp = $dir_media.'comp/'.$med['id'].'.mp4'; #Caminho comprimido
                                $caminho_conv = $dir_media.'conv/'.$med['id'].'.mp4'; #Caminho convertido

                                echo "
                                <tr>
                                    <th scope='row'><img class='shadow rounded-1' width='64' src='".$url_media."thumb/".$med['thu'].".jpg'></th>
                                    <td><a class='text-primary text-decoration-none' href='/m/".$med['id']."'><b>".$med['tit']."</b></a></td>
                                    <td><a class='text-primary text-decoration-none' href='/u/".$med_uti['nut']."'><b>".$med_uti['nut']."</b></a></td>
                                    <td>".filesize($caminho_ori)."</td>
                                    <td>".filesize($caminho_comp)."</td>
                                    <td>".filesize($caminho_conv)."</td>
                                    <td><button id='btn_reset_".$med['id']."' onclick='reset(`".$med['id']."`)' class='btn btn-primary'><i class='bi bi-arrow-clockwise'></i></button></td>
                                </tr>
                                ";

                            } 
                            $resultado->free();
                        }

                        echo "
                    </table>
                </div>
                
            </div>
            ";
            }

            #Utilizadores
            echo "
			<div class='bg-light text-dark'>
                <div class='d-md-flex p-xl-5 p-4'>
                    <div class=''>
                        <h4>"._("Utilizadores")."</h4>
                    </div>
                    <div class='ms-auto'>
                        <form method='get'>
                            <div class='input-group'>
                                <input name='p_uti' type='text' placeholder='"._("Procurar")."' value='".$_GET['p_uti']."' class='form-control bg-dark bg-opacity-50'>
                                <button type='submit' class='btn btn-primary' id='button-addon2'><i class='bi bi-search'></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class='table-responsive px-2'>
                    <table class='table table-light'>
                        <tr class='opacity-75'>
                            <th scope='col'>Foto</th>
                            <th scope='col'>Utilizador</th>
                            <th scope='col'>Email</th>
                            <th scope='col'>Data de criação</th>
                            <th scope='col'>Ativo</th>
                            <th scope='col'>Mod</th>
                        </tr>
                        ";
                        if ($_GET['p_uti']){
                            $p_uti = "SELECT * FROM uti WHERE nut LIKE '%".$_GET['p_uti']."%' OR nco LIKE '%".$_GET['p_uti']."%' ORDER BY dcr DESC;";
                        } else {
                            $p_uti = "SELECT * FROM uti ORDER BY dcr DESC;";
                        }
                        if ($resultado = $bd->query($p_uti)) {
                            while ($campo = $resultado->fetch_assoc()) {

                                if ($campo['mai']){
                                    $mail = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id=".$campo['mai'].";"));
                                    $campo['mai'] = $mail;
                                }

                                if ($campo['mai']['con']==1){
                                    $mail_confirmado = "<span class='text-verde me-2'>⬤</span>";
                                } else {
                                    $mail_confirmado = "<span class='text-vermelho me-2'>⬤</span>";
                                }
                                
                                echo "
                                <tr>
                                    <td><a class='text-primary' href='/u/".$campo['nut']."'><img class='rounded-circle' src='".$url_media."fpe/".$campo['fpe'].".jpg' width='40' height='40'></a></td>
                                    <td><b>".$campo['nut']." </b> (".$campo['id'].")<br>".$campo['nco']."</td>
                                    <td>".$mail_confirmado.$campo['mai']['mai']."</td>
                                    <td>".$campo['dcr']."</td>
                                    <td><div class='form-check form-switch'>
                                    <input type='checkbox' role='switch' class='form-check-input' ";
                                    if ($campo['ati']==1){ echo "checked "; }
                                    if ($campo['id']==$uti['id']){ echo "disabled "; }
                                    echo "id='ati".$campo['id']."'>
                                    <label class='form-check-label' for='ati".$campo['id']."'></label>
                                    </div></td>
                                    <td><div class='form-check form-switch'>
                                    <input type='checkbox' role='switch' class='form-check-input' ";
                                    if ($campo['car']==2){ echo "checked "; }
                                    if ($campo['id']==$uti['id']){ echo "disabled "; }
                                    echo "id='car".$campo['id']."'>
                                    <label class='form-check-label' for='car".$campo['id']."'></label>
                                    </div></td>
                                </tr>
                                ";
                            } 
                            $resultado->free();
                        }
                        echo "
                    </table>
                </div>
			</div>

		</div>
		";
		?>

        <script>
            $(':checkbox').change(function(){
				res = api('adm',{'campo':this.id});
                console.debug(res);
            });

            function reset(med){
				res = api('err_comp',{'med':med});
                console.debug(res);
                if (res.est=='sucesso'){
                    $("#btn_reset_"+med).html("<i class='bi bi-check2'></i>");
                }
            }
        </script>

	</body>
</html>