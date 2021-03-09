<?php
require('pro/fun.php');

$texto = addslashes($_POST['texto']);
$id = $_POST['id'];

if ($bd->query("UPDATE pro_sec SET tex='".$texto."' WHERE id='".$id."'") === FALSE) {
	echo "Error:".$bd->error;
}

echo $texto;
exit;
?>