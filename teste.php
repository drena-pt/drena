<?php
use \EditorJS\EditorJS;
require './vendor/autoload.php';

require('pro/fun.php');
$pro_sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='1'"));
$configuration = "{
	'tools': {
	  'header': {
		'text': {
		  'type': 'string',
		  'required': true,
		  'allowedTags': 'b,i,a[href]'
		},
		'level': {
		  'type': 'int',
		  'canBeOnly': [2, 3, 4]
		}
	  }
	}
  }
";

$data = json_decode($pro_sec['tex']);
echo "ola";
try {
	// Initialize Editor backend and validate structure
	$editor = new EditorJS( $data, "" );
	// Get sanitized blocks (according to the rules from configuration)
	$blocks = $editor->getBlocks();	
} catch (\EditorJSException $e) {
	// process exception
}
?>