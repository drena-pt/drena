<?php
require 'fun.php'; #Funções

if ($uti['nut']!='guilhae'){
	echo "Não podes aceder a esta pagina!";
	exit;
}
?>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>
	<?php
	echo "
	<table class='titulo'>
	<tr><td>Base de dados:</td><td>drena</td></tr>
	<tr><td>Tabela:</td><td>uti_fot</td></tr>
	<tr><td>Utilizador:</td><td>".$_SESSION["uti"]."</td></tr>
	</table>
	<br>
	<table>
	<tr><td colspan='2'><h1>Página ".$_GET["tab"]."</h1></td></tr>";
	if ($_GET["tab"] !=0){
		echo "
		<tr><td><a href='?tab=".($_GET["tab"]-1)."'>Voltar ".($_GET["tab"]-1)."</a></td>";
	}
	echo "
	<td><a href='?tab=".($_GET["tab"]+1)."'>Ir ".($_GET["tab"]+1)."</a></td></tr>
	</table>
	<br>
	<table>
	<tr class='principal'><td>id</td><td>uti</td><td>nom</td><td>den</td><td>fot</td></tr>";
		
		$oset = $_GET["tab"]*10;
		$query = "SELECT * FROM uti_fot LIMIT 10 OFFSET $oset";
		
		
		if ($result = $bd->query($query)) {
			while($row = mysqli_fetch_array($result)){
				$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row['uti']."'"));
				echo "<tr><td>".$row['id']."</td><td>".$uti['nut']."</td><td>".$row['nom']."</td><td>".$row['den']."</td><td><img width='32' src='/fpe/".base64_encode($row['id'])."'></td></tr>";
			}
		}
		?>
	</table>
</body>
</html>