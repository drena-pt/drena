<?php
require '../fun.php'; #Funções

if ($uti['nut']!='guilhae'){
	echo "Não podes aceder a esta pagina!";
	exit;
}
?>
<html>
<head>
	<meta charset="UTF-8">
	<style>
		body{
			font-family: "Lucida Console", "Menlo", "Monaco", "Courier", monospace;
		}
		table{
			background-color: #ddd;
			border-collapse: separate;
  			border-spacing: 20 0;
		}
	</style>
</head>
<body>
	<?php
	echo "
	<table><tr><td>Tabela:</td><td>uti_fpe</td></tr></table>
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
	<tr><td><b>id</b></td><td><b>uti</b></td><td><b>den</b></td><td><b>img</b></td></tr>";
		
		$oset = $_GET["tab"]*10;
		$query = "SELECT * FROM uti_fpe ORDER BY den LIMIT 10 OFFSET $oset";
		
		
		if ($result = $bd->query($query)) {
			while($row = mysqli_fetch_array($result)){
				$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$row['uti']."'"));
				echo "<tr><td>".$row['id']."</td><td>".$uti['nut']."</td><td>".$row['den']."</td><td><img width='32' src='".$url_media."fpe/".$row['id'].".jpg'></td></tr>";
			}
		}
		?>
	</table>
</body>
</html>