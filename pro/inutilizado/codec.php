<?php
$codec = exec("ffprobe -v error -select_streams v:0 -show_entries stream=codec_name -of default=noprint_wrappers=1:nokey=1 /home/guilha/www/media.drena.xyz/ori/".$_GET['id']);
echo $codec;
?>