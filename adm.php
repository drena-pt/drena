<?php 
		require('head.php');
		if ($uti['adm']==0){ header("Location: /"); exit; }	#Sair da página se não for administrador
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
                        <h3>Utilizadores</h3>
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
                        <th scope='col'>Utilizador</th>
                        <th scope='col'>Nome</th>
                        <th scope='col'>Data de criação</th>
                        <th scope='col'>Ativo</th>
                        <th scope='col'>Admin</th>
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
                                    <td><img class='rounded-circle' src='fpe/".base64_encode($campo["fot"])."' width='40' height='40'></td>
                                    <td><a href='/perfil?uti=".$campo['nut']."'>".$campo['nut']."</a></td>
                                    <td>".$campo['nco']."</td>
                                    <td>".$campo['dcr']."</td>
                                    <td><div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' ";
                                    if ($campo['ati']==1){ echo "checked "; }
                                    if ($campo['id']==$uti['id']){ echo "disabled "; }
                                    echo "id='ati".$campo['id']."'>
                                    <label class='custom-control-label' for='ati".$campo['id']."'></label></div></td>

                                    <td><div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' ";
                                    if ($campo['adm']==1){ echo "checked "; }
                                    if ($campo['id']==$uti['id']){ echo "disabled "; }
                                    echo "id='adm".$campo['id']."'>
                                    <label class='custom-control-label' for='adm".$campo['id']."'></label></div></td>
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