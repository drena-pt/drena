		<?php 
		require('head.php');
		if (!$uti){
			header("Location: /entrar.php");
			exit;
		}
		?>
	</head>
	<body>
		<?php 
		require('cabeçalho.php');
		echo "
		<div class='p-0 mt-0 mt-xl-4 col-xl-6 offset-xl-3'>
			<text><span class='h1 fw-bold'>Escritura </span><span class='badge rounded-pill bg-white text-dark'>Beta</span></text>
			<section class='text-start'>
				<button class='btn btn-dark text-light' data-bs-toggle='collapse' data-bs-target='#collapseExample' aria-expanded='true' aria-controls='collapseExample'>
				Criar novo roteiro <i class='bi bi-plus-circle'></i>
				</button>
			</section>


			<section id='collapseExample' class='mb-2 bg-dark text-light shadow collapse' style=''>
				<div class='p-xl-5 p-4'>
					<h3>Criar novo roteiro</h3>
				
					<text class='h5'>Título</text>
					<form class='row' method='post' action='/' id='criar_roteiro'>
						<div class='col-sm-6 col-auto'>
							<input type='text' class='form-control' id='alb_tit_input' name='rot_tit' placeholder='".sprintf(_('Roteiro de %s'),$uti['nut'])."' maxlength='40'>
						</div>
						<div class='col-auto'>
							<button class='btn btn-light'>Criar</button>
						</div>
					</form>

					<script>
					$('#criar_roteiro').submit(function(event) {
					
						// Stop form from submitting normally
						event.preventDefault();
						
						// Get some values from elements on the page:
						var term = $(this).find(\"input[name='rot_tit']\").val();
						
						// Send the data using post
						var url = '/api/rot.php?uti=".$uti['nut']."&cod=".$uti_mai['cod']."&tit='+term;

						console.log(url);

						var posting = $.post(url);
						
						// Put the results in a div
						posting.done(function(data){
							if (data['err']){
								$('#tit').text(data['err']);
							} else if (data['rot']) {
								window.open('https://escritura.drena.pt/'+data['rot'],'_self');
							}
						});
					});
					</script>
				</div>
			</section>";
            
			$rot_pesquisa = "SELECT * FROM rot WHERE uti='".$uti['id']."' ORDER BY dcr DESC";
            if ($resultado = $bd->query($rot_pesquisa)) {

                echo "<div class='row row-cols-1 row-cols-md-2'>";

                while ($campo = $resultado->fetch_assoc()) {
                    #Define o nome a aparecer
                    if (!$campo['tit']){$rot_tit=sprintf(_('Roteiro de %s'),$uti['nut']);}else{$rot_tit=$campo['tit'];}

                    echo"
                    <div class='col'><a class='text-decoration-none' href='https://escritura.drena.pt/".$campo['id']."' ><div class='bg-light bg-cover text-dark h5 p-xl-5 p-4 mb-4 rounded-xl shadow d-flex justify-content-between align-items-center'>
                        ".$rot_tit."
                        <span class='badge rounded-pill bg-dark text-light'>".bytesParaHumano(strlen($campo['rot']))."</span>
                    </div></a></div>
                    ";
                } 
                $resultado->free();
                echo "</div>";
            }
		
		echo "
		</div>";
		?>
	</body>
</html>