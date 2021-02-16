<link href="node_modules/video.js/dist/video-js.css" rel="stylesheet" />
<script src="node_modules/video.js/dist/video.min.js"></script>
<link href="node_modules/@silvermine/videojs-quality-selector/dist/css/quality-selector.css" rel="stylesheet">
<script src="node_modules/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js"></script>

<video poster="imagens/thumb.jpg" id="video_1" width='640px' height='360px' class="video-js vjs-default-skin" controls preload="auto" data-setup='{}'>
   <source src="imagens/GH010122-1080.MP4" type="video/mp4" label="1080P" selected="true">
   <source src="imagens/GH010122-480.mp4" type="video/mp4" label="480P">
   <source src="imagens/GH010122-240.mp4" type="video/mp4" label="240P">
</video>
<script>
   videojs("video_1", {}, function() {
      var player = this;

      player.controlBar.addChild('QualitySelector');
   });
</script>
