<?php 
require('head.php');
if ($uti['car']!=2){ header("Location: /"); exit; }	#Sair da página se não for moderador
function mini_nut($nut){
    if (strlen($nut)>=12){
        return (substr($nut, 0, 10)."…");
    } else {
        return ($nut);
    }
}
?>
        <style>
        table {
            font-size: .875em;
        }
        </style>
	</head>
	<body>
	<?php require('header.php'); ?>
	<?php
	echo "
	<div class='shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3'>

        <div class='p-xl-5 p-4 bg-dark text-light'>
			<h2 class='text-ciano'>"._('Ferramentas de Moderador')."</h2>
            <a href='/mod_espaco' class='btn btn-light'><i class='bi bi-hdd'></i>"._("Espaço usado")."</a>
		</div>

		<div class='bg-light text-dark'>

            <div class='d-flex bd-highlight p-xl-5 p-4'>
                <div class='me-auto p-2 bd-highlight'><h3>Média por confirmar</h3></div>
                <div class='my-auto p-2 bd-highlight'><span role='button' data-bs-toggle='modal' data-bs-target='#modal_info1'><i class='bi bi-info-circle-fill text-primary' data-bs-toggle='tooltip' data-bs-html='true' title='"._('Informação')."'></i></span></div>
                
                <!-- Modal info (Média por confirmar) -->
                <div class='modal fade' id='modal_info1' tabindex='-1' role='dialog' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content bg-primary bg-gradient rounded-xl shadow p-5 text-light'>
                            <form action='procurar' method='get'>
                                <div class='modal-header'>
                                    <h2 class='modal-title'>"._('Informação')." <i class='bi bi-info-circle'></i></h2><br><br>
                                </div>
                                <div class='modal-body'>
                                Aqui aparecem médias que foram reportadas por outros moderadores, abre a média e caso concordes com a ação a tomar podes confirmar.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class='table-responsive px-2'>
                <table class='table table-light'>
                    <tr>
                    <th scope='col'>Thumbnail</th>
                    <th scope='col'>Título</th>
                    <th scope='col'>Tipo</th>
                    <th scope='col'>Ação</th>
                    </tr>
                    ";
                    $pesquisa = "SELECT * FROM med WHERE nmo IN (1,3,4)";
                    if ($resultado = $bd->query($pesquisa)) {
                        while ($campo = $resultado->fetch_assoc()){

                            #Define a ação a tomar em texto
                            if ($campo['nmo']==1){
                                $campo_ac = "Definir como sensível";
                            } else if ($campo['nmo']==3){
                                $campo_ac = "Definir como contéudo inaceitável";
                            } else if ($campo['nmo']==4){
                                $campo_ac = "Eliminar contéudo inaceitável";
                            }

                            echo "
                            <tr>
                                <th scope='row'><img class='shadow rounded-xl' width='128' src='".$url_media."thumb/".$campo['thu'].".jpg'></th>
                                <td><a href='/m/".$campo['id']."'>".$campo['tit']."</a></td>
                                <td>";
                                switch($campo['tip']){
                                    case 1: echo "<i class='bi bi-film text-primary'></i>"; break;
                                    case 2: echo "<i class='bi bi-soundwave text-rosa'></i>"; break;
                                    case 3: echo "<i class='bi bi-image text-ciano'></i>"; break;
                                    default: echo "?"; break;
                                }
                                echo "</td>
                                <td>".$campo_ac."</td>
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
	</body>
</html>