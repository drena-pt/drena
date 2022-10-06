	<?php 
	require('head.php');
	if (!$uti){
		header("Location: /entrar.php"); exit;
	}
	?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div class="shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3">
			<div class="p-xl-5 p-4 bg-dark text-light">

				<a class="float-end btn btn-light" href='/pro/sair'><?php echo _('Terminar Sessão');?> <i class="bi bi-box-arrow-right"></i></a>

				<div class="d-flex">
                    <div>
						<img class="rounded-circle" src='fpe/<?php echo base64_encode($uti["fot"]);?>' width="128" height="128">
                    </div>
                    <div class="ms-3">
						<h2><?php echo $uti["nut"];?></h2>
						<text class="h5"><?php echo $uti["nco"];?></text><br>
						<text><?php echo _('Criação da conta').": ".substr($uti["dcr"], 0, -9);?></text>
                    </div>
                </div>
			</div>

			<div class="p-xl-5 p-4 bg-light text-dark">
				<h2 class='pb-3'>Definições</h2>

				
				<?php
				/*echo "Idioma
				<div class='row'>
					<div class='col-4 mr-auto'>
						<select class='form-control text-dark border-dark' style='cursor:pointer;'>
							<option>Deutsch</option>
							<option>English</option>
							<option>Français</option>
							<option>Italiano</option>
							<option selected>Português</option>
						</select>
					</div>
					<div class='col-auto p-0'><button class='btn btn-dark'>"._('Guardar')."</button></div>
				</div>";*/

				echo "
				<div class='mb-4'>
					Email<br>
					<div class='row'>
						<div class='col-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='".$uti_mai['mai']."'>
							
						</div>
						<div class='col-auto p-0'><a href='/registo?ac=alterarMail' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>
				
				
				<div class='mb-4'>
					"._('Palavra-passe')."<br>
					<div class='row'>
						<div class='col-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='••••••••••••'>
							
						</div>
						<div class='col-auto p-0'><a href='/entrar?ac=alterarPasse&uti=".$uti['nut']."&cod=".$uti_mai['cod']."' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>
				";
				?>
				
			</div>
		</div>
	</body>
</html>