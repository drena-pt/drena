<?php 
		require('head.php');
		if ($uti['car']!=1){ header("Location: /"); exit; }	#Sair da página se não for administrador
		?>
        <script src='/js/api.min.js'></script>
        <style>
        table {
            font-size: .875em;
        }
        </style>
	</head>
	<body>
	    <?php require('cabeçalho.php'); ?>
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

                <div class='row row-cols-3 mt-4'>
                    <div class='col text-center'>
                        <div class='h4 mb-2 m-auto d-flex align-items-center justify-content-center rounded-circle bg-opacity-10 border bg-ciano border-ciano' style='height: 100px; width: 100px'>
                        ".$num_uti."
                        </div>
                        Utilizadores
                    </div>
                    <div class='col text-center'>
                        <div class='h4 mb-2 m-auto d-flex align-items-center justify-content-center rounded-circle bg-opacity-10 border bg-primary border-primary' style='height: 100px; width: 100px'>
                        ".$num_med."
                        </div>
                        Médias
                    </div>
                    <div class='col text-center'>
                        <div class='h4 mb-2 m-auto d-flex align-items-center justify-content-center rounded-circle bg-opacity-10 border bg-rosa border-rosa' style='height: 100px; width: 100px'>
                        ".$tam_media."
                        </div>
                        Espaço usado
                    </div>
                </div>
			</div>

			<div class='bg-light text-dark'>
                <div class='d-md-flex p-xl-5 p-4'>
                    <div class=''>
                        <h3>Utilizadores</h3>
                    </div>
                    <div class='ms-auto'>
                        <form method='get'>
                            <div class='row'>
                                <div class='col-auto'>
                                    <input name='pesquisa' class='border-0 form-control bg-dark' type='text' placeholder='Pesquisar...'></input>
                                </div>
                                <div class='col-auto d-flex'>
                                    <button type='submit' class='btn btn-primary'>Pesquisar <i class='bi bi-search'></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class='table-responsive px-2'>
                    <table class='table table-light'>
                        <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>Foto</th>
                        <th scope='col'>Utilizador</th>
                        <th scope='col'>Nome</th>
                        <th scope='col'>Data de criação</th>
                        <th scope='col'>Ativo</th>
                        <th scope='col'>Mod</th>
                        </tr>
                        ";
                        if ($_GET['pesquisa']){
                            $pesquisa = "SELECT * FROM uti WHERE nut LIKE '%".$_GET['pesquisa']."%' OR nco LIKE '%".$_GET['pesquisa']."%'";
                        } else {
                            $pesquisa = "SELECT * FROM uti";
                        }
                        if ($resultado = $bd->query($pesquisa)) {
                            while ($campo = $resultado->fetch_assoc()) {
                                echo "
                                <tr>
                                    <th scope='row'>".$campo['id']."</th>
                                    <td><img class='rounded-circle' src='".$url_media."fpe/".$campo['fpe'].".jpg' width='40' height='40'></td>
                                    <td><a class='text-primary' href='/u/".$campo['nut']."'>".$campo['nut']."</a></td>
                                    <td>".$campo['nco']."</td>
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
                <script>
                $(':checkbox').change(function(){
					result = api('adm',{'campo':this.id});
                    console.log(result);
                });   
                </script>
                <span id='data'></span>
			</div>

            <div class='bg-light text-dark'>
                <div class='d-md-flex p-xl-5 p-4'>
                    <div class=''>
                        <h3>Projetos</h3>
                    </div>
                    <div class='ms-auto'>
                        <form method='get'>
                            <div class='row'>
                                <div class='col-auto'>
                                    <input name='pesquisa_pro' class='border-0 form-control bg-dark' type='text' placeholder='Pesquisar...'></input>
                                </div>
                                <div class='col-auto d-flex'>
                                    <button type='submit' class='justify-content-center align-self-center btn btn-primary'>Pesquisar <i class='bi bi-search'></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class='table-responsive px-2'>
                    <table class='table table-light'>
                        <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>Utilizador</th>
                        <th scope='col'>Título</th>
                        <th scope='col'>Cor</th>
                        <th scope='col'>Privado</th>
                        <th scope='col'>Ativo</th>
                        <th scope='col'>Secções</th>
                        <th scope='col'>Data de criação</th>
                        </tr>
                        ";
                        if ($_GET['pesquisa_pro']){
                            $pesquisa_pro = "SELECT * FROM pro WHERE tit LIKE '%".$_GET['pesquisa_pro']."%'";
                        } else {
                            $pesquisa_pro = "SELECT * FROM pro";
                        }
                        if ($resultado_pro = $bd->query($pesquisa_pro)) {
                            while ($campo = $resultado_pro->fetch_assoc()) {
                                $pro_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$campo["uti"]."'"));	
                                echo "
                                <tr>
                                    <th scope='row'>".$campo['id']."</th>
                                    <td><a href='/u/".$pro_uti['nut']."' title='".$pro_uti['nut']."'><img class='rounded-circle' src='".$url_media."fpe/".$pro_uti['fpe'].".jpg' width='40' height='40'></a></td>
                                    <td><a href='/projeto?id=".base64_encode($campo['id'])."'>".$campo['tit']."</a></td>
                                    <td>".$campo['cor']."</td>
                                    <td>".$campo['pri']."</td>
                                    <td>".$campo['ati']."</td>
                                    <td>";
                                    if ($resultado_pro_sec = $bd->query("SELECT * FROM pro_sec WHERE pro='".$campo['id']."'")) {
                                        while ($campo = $resultado_pro_sec->fetch_assoc()) {
                                            echo $campo['id']." ";
                                        }
                                    }
                                    echo "</td>
                                    <td>".$campo['dcr']."</td>
                                </tr>
                                ";
                            } 
                            $resultado_pro->free();
                        }
                        echo "
                    </table>
                </div>
                <span id='data'></span>
			</div>
		</div>
		";
		?>
	</body>
</html>