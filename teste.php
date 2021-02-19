<?php 
require('head.php');
$ultimo_video = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med ORDER BY den DESC LIMIT 1"));
header("Location: /video?id=".$ultimo_video['id']);
?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<!--<div class='text-dark p-5 my-4 col-xl-6 offset-xl-3'>
			<div class='card-deck'>
				<div class="card">
					<img src="..." class="card-img-top" alt="...">
						<div class="card-body">
						<h5 class="card-title">Card title</h5>
						<p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
						<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
					</div>
				</div>
				<div class="card">
					<img src="..." class="card-img-top" alt="...">
						<div class="card-body">
						<h5 class="card-title">Card title</h5>
						<p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
						<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
					</div>
				</div>
			</div>
		</div>-->
		
	</div>
	</body>
</html>