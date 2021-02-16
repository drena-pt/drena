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
		<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>
			<div class='p-xl-5 p-4 bg-dark text-light'>
				<h2>Ferramentas de Administrador</h2>
			</div>
			<div class='p-xl-5 p-4 bg-light text-dark'>
                <div class='d-flex mb-3'>
                    <div class=''>
                        <h3>Vídeo</h3>
                    </div>
                    <div class='ml-auto'>
                        <form method='get'>
                            <div class='form-row'>
                                <div class='col-auto'>
                                    <input name='pesquisa' class='border-0 form-control bg-dark' type='text' placeholder='Pesquisar...'></input>
                                </div>
                                <div class='col-auto d-flex'>
                                    <button type='submit' class='justify-content-center align-self-center btn btn-primary'>Pesquisar <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#search'/></svg></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class='table-responsive'>
                    <table class='table text-dark'>
                        <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>Foto</th>
                        <th scope='col'>Nome original</th>
                        <th scope='col'>Tipo</th>
                        <th scope='col'>Utilizador</th>
                        <th scope='col'>Data de envio</th>
                        </tr>
                        ";
                        if ($_GET['pesquisa']){
                            $pesquisa = "SELECT * FROM med WHERE nom LIKE '%".$_GET['pesquisa']."%' order by den";
                        } else {
                            $pesquisa = "SELECT * FROM med order by den";
                        }
                        if ($resultado = $bd->query($pesquisa)) {
                            while ($campo = $resultado->fetch_assoc()) {
                                echo "
                                <tr>
                                    <th scope='row'>".$campo['id']."</th>
                                    <td><img src='http://media.drena.xyz/thumb/".$campo['id'].".jpg' width='40'></td>
                                    <th scope='row'>".$campo['nom']."</th>
                                    <th scope='row'>".$campo['tip']."</th>
                                    <th scope='row'>".$campo['uti']."</th>
                                    <th scope='row'>".$campo['den']."</th>
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
                    $.ajax({
                        url: 'pro/adm.php?campo='+this.id,
                        success: function(data){
                            $('#data').text(data);
                        },
                        error: function(){
                            alert('There was an error.');
                        }
                    });
                });   
                </script>
                <span id='data'></span>
			</div>
		</div>
		";
		?>
		</div>
	</body>
</html>