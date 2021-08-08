<?php 
require('head.php');

# script 'notificacoes.js é o registo no sistema de notificações
if ($uti){
    $uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti["mai"]."'"));
    echo "<script>const sub_uti_nut='".$uti['nut']."';const sub_uti_cod='".$uti_mai['cod']."';</script><script src='/js/notificacoes.js'></script>";
}
?>
    <meta name="description" content="Website de partilha de projetos, vídeo, música e imagens. Partilha o teu trabalho livremente na drena.">
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
			<style>
			.jumbotron{
				height: 90vh;
				background-image: linear-gradient(-90deg,rgba(0,0,0,0.6),rgba(0,0,0,0),rgba(0,0,0,0.6)),url("imagens/fundo.jpg");
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
			}
			</style>
			<?php
            if (!$uti){
                $index_titulos = ['pt' => "MOSTRA O QUE FAZES.",'en' => "SHOW WHAT YOU MAKE.",'de' => "ZEIG WAS DU MACHST.",'it' => "MOSTRA COSA FAI.",'fr' => "MONTREZ CE QUE VOUS FAITES."];
                unset($index_titulos[get_browser_language()]);
    
                echo "
                <div class='jumbotron bg-dark d-flex align-items-center text-center justify-content-center align-items-center'>
                    <h1 class='display-4'>".strtoupper(_("Mostra o que fazes."))."<br>
                    <span class='text-outline'>";
                    foreach($index_titulos as $titulo){
                        echo $titulo."<br>";
                    }
                    echo "
                    </span>
                    <a href='/registo' role='button' class='btn btn-primary'>"._('Criar uma conta')."</a>
                    </h1>
    
                    
                </div>
    
                <div class='p-0 my-0 my-xl-4 col-xl-6 offset-xl-3 text-center'>
                    <h1 class='display-3 m-5'>".strtoupper(_('Ultimos vídeos'))."</h1>
                    <div class='row row-cols-2 row-cols-md-3 mw-100'>
                    ";
                    $pesquisa = "SELECT * FROM med WHERE tip='1' AND pri=0 ORDER by den DESC LIMIT 12";
                    if ($resultado = $bd->query($pesquisa)) {
                        while ($campo = $resultado->fetch_assoc()) {
                            if ($campo['tit']){$video_tit = $campo['tit'];} else {$video_tit = $campo['nom'];}
                            echo "
                            <div class='col mb-4 container'>
                                <a class='text-light' href='/media?id=".$campo['id']."'>
                                    <div class='rounded-xl inset-shadow'>
                                        <img class='shadow rounded-xl w-100' src='".$url_media."thumb/".$campo['thu'].".jpg'>
                                        <div class='texto-container-bottom'><text class='h6'>".encurtarNome($video_tit)."</text></div>
                                    </div>
                                </a>
                            </div>
                            ";
                        } 
                        $resultado->free();
                    }
                    echo "
                    </div>
                </div>";
            } else {
                echo "<div class='p-0 col-xl-6 offset-xl-3 text-center'>";

                $sql_conhecidos = "SELECT * FROM ami WHERE a_id='".$uti['id']."' AND sim='1' OR b_id='".$uti['id']."' AND sim='1' ORDER by b_dat DESC";
                $conhecidos = mysqli_fetch_assoc(mysqli_query($bd, $sql_conhecidos));
                $lista_feed = $uti['id'];
                if ($conhecidos){
                    if ($resultado = $bd->query($sql_conhecidos)) {
                        while ($campo = $resultado->fetch_assoc()){
                            #Adiciona os utilizadores à lista
                            if ($campo['a_id']==$uti['id']){
                                $lista_feed .= ','.$campo['b_id'];
                            } else {
                                $lista_feed .= ','.$campo['a_id'];
                            }
                        }
                    }
                } else {
                    echo "<div class='my-4'>
                    <h2>Ainda não tens conhecidos</h2>
                    <span data-toggle='modal' data-target='#modal_procurar'><button class='btn btn-primary'>"._('Procurar')."<i class='bi bi-search'></i></button></span>
                    </div>";
                }

                echo "
                    <div class='my-4'>";
                    if ($conhecidos){
                        if ($_GET['feed']=='global'){
                            $api_link = $url_site."api/feed?uti=".$uti['id']."&tip=global";
                            echo "<a href='/' class='btn btn-light' role='button'>Feed <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#view-stacked'/></a>
                            <a class='btn btn-primary' role='button'>Feed global <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#globe'/></svg></a>";
                        } else {
                            $api_link = $url_site."api/feed?uti=".$uti['id'];
                            echo "<a class='btn btn-primary' role='button'>Feed <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#view-stacked'/></a>
                            <a href='/?feed=global' class='btn btn-light' role='button'>Feed global <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#globe'/></svg></a>";
                        }
                    } else {
                        $api_link = $url_site."/api/feed?tip=global";
                    }
                        echo "<!--<a href='feed?tip=global' role='button' class='btn btn-light'>Explorar <svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#compass'/></svg></a>-->
                    </div>
                    ";

                    if ($_GET['feed']!='global' AND $conhecidos){
                        echo "
                        <div class='row mx-0 mx-xl-2'>
                            <div class='col-2'></div>
                            <div class='col-8'>";
                            if ($resultado = $bd->query("SELECT * FROM uti WHERE id IN (".$lista_feed.") ORDER by id DESC LIMIT 8")){
                                while ($campo_uti = $resultado->fetch_assoc()){
                                    echo "<a data-toggle='tooltip' data-placement='bottom' title='".$campo_uti['nut']."' href='/perfil?uti=".$campo_uti['nut']."'><img src='fpe/".base64_encode($campo_uti["fot"])."' class='mx-1 rounded-circle' width='32'></a>";
                                }
                            }
                        echo "</div>
                            <div class='col-2'></div>
                        </div>";
                    }

                    echo "
                    <div id='medias'></div>

                    <script>
                    let scrollLoad = true;
                    let api_link = '".$api_link."';

                    function tempoPassado(tempo) {
                        var passado = Date.now() - new Date(tempo);

                        var mspSEG = 1000;
                        var mspMIN = mspSEG * 60;
                        var mspHOR = mspMIN * 60;
                        var mspDIA = mspHOR * 24;
                        var mspSEM = mspDIA * 7;
                        var mspMES = mspDIA * 30;
                        var mspANO = mspDIA * 365;

                        const tTempo = {
                            'mspSEG':['"._('segundo')."','"._('segundos')."'],
                            'mspMIN':['"._('minuto')."','"._('minutos')."'],
                            'mspHOR':['"._('hora')."','"._('horas')."'],
                            'mspDIA':['"._('dia')."','"._('dias')."'],
                            'mspSEM':['"._('semana')."','"._('semanas')."'],
                            'mspMES':['"._('mês')."','"._('meses')."'],
                            'mspANO':['"._('ano')."','"._('anos')."']
                        };
                        
                        if (passado < mspMIN) { uTempo=mspSEG; }
                        else if (passado < mspHOR) { uTempo='mspMIN'; }
                        else if (passado < mspDIA) { uTempo='mspHOR'; }
                        else if (passado < mspSEM) { uTempo='mspDIA'; }
                        else if (passado < mspMES) { uTempo='mspSEM'; }
                        else if (passado < mspANO) { uTempo='mspMES'; }
                        else { uTempo=mspANO; }

                        vuTempo = eval(window['uTempo']);

                        if (passado/vuTempo<2){
                            return (Math.floor(passado/vuTempo)+' '+tTempo[uTempo][0]);
                        } else {
                            return (Math.floor(passado/vuTempo)+' '+tTempo[uTempo][1]);
                        } 
                    }

                    function carregarMedia(url){
                        $.get(url, function(data) {
                            if (data){
                                if (data.erro!=null){
                                    console.log('ERRO: '+data.erro);
                                } else {
                                    for(var index = 0; index < data.length; index++) {
                                        
                                        //Carrega a média apenas se não for repetida
                                        if (!$('#med_'+data[index].med.id+'_conteudo').length){
                                            $('#medias').append(\"<section class='bg-preto bg-gradient shadow my-4'><div class='mw-100' id='med_\"+data[index].med.id+\"_conteudo'></div><div class='p-xl-5 p-4'><div class='row mb-3'><div class='col-12 col-md d-flex'><text class='h5 my-auto' id='med_tit'>\"+data[index].med.tit+\"</text></div></div><section class='mt-auto'><div class='row mb-1'><div class='col-auto pe-0 text-center'><a href='/perfil?uti=\"+data[index].uti.nut+\"'><img src='fpe/\"+btoa(data[index].uti.fot)+\"' class='rounded-circle' width='40'></a></div><div class='col d-flex'><span class='justify-content-center align-self-center'>"._('Publicado por')." \"+data[index].uti.nut+\"</span></div></div><div class='row mb-1'><div class='col-auto pe-0 text-center'><svg onclick='gosto(`\"+data[index].med.id+\"`)' class='bi' style='cursor:pointer;' width='1em' height='1em' fill='currentColor'><use id='svg_gosto_\"+data[index].med.id+\"' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up-fill'/><use id='svg_naoGosto_\"+data[index].med.id+\"' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up'/></svg></div><div class='col d-flex'><span id='med_\"+data[index].med.id+\"_numGostos'>\"+data[index].med.gos+\"</span>&nbsp;"._('gostos')."</div></div><div class='row mb-1'><div class='col-auto pe-0 text-center'><svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#calendar4-week'/></svg></div><div class='col d-flex'>".sprintf(_('há %s'),"\"+tempoPassado(data[index].med.den)+\"")."</div></div></section></div></section>\");
                                            
                                            if (data[index].uti.gos==1){
                                                $('#svg_naoGosto_'+data[index].med.id).attr('hidden', true);
                                            } else {
                                                $('#svg_gosto_'+data[index].med.id).attr('hidden', true);
                                            }
    
                                            if (data[index].med.tip==1){
                                                $('#med_'+data[index].med.id+'_conteudo').html(\"<div style='position:relative;padding-bottom:56.25%;'><iframe style='position:absolute;top:0;left:0;width:100%;height:100%;' src='/embed?id=\"+data[index].med.id+\"&titulo=0'></iframe></div>\");
                                            } else if (data[index].med.tip==2){
                                                $('#med_'+data[index].med.id+'_conteudo').html(\"<iframe height='180px' class='w-100' src='/embed?id=\"+data[index].med.id+\"&titulo=0'></iframe>\");
                                            } else {
                                                $('#med_'+data[index].med.id+'_conteudo').html(\"<iframe style='min-height:50vh;' class='w-100' src='/embed?id=\"+data[index].med.id+\"&titulo=0'></iframe>\");
                                            }
                                            api_link = '".$api_link."&depois='+data[index].med.id;
                                            scrollLoad = true;
                                        } else {
                                            //console.log(data[index].med.id+' Média repetida ocultada');
                                            if (index+1==data.length){
                                                console.log('Aviso: Fim da lista.');
                                                scrollLoad = false;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                    carregarMedia(api_link);

                    function gosto(med_id){
                        $.ajax({
                            url: 'pro/med_gos.php?med='+med_id,
                            success: function(result) {
                                var gostos = +$('#med_'+med_id+'_numGostos').text();
                                console.log('gosto med('+med_id+') '+result);
                                if (result==='false'){
                                    $('#svg_gosto_'+med_id).attr('hidden', true);
                                    $('#svg_naoGosto_'+med_id).removeAttr('hidden');
                                    $('#med_'+med_id+'_numGostos').text(gostos-1);
                                } else {
                                    $('#svg_gosto_'+med_id).removeAttr('hidden');
                                    $('#svg_naoGosto_'+med_id).attr('hidden', true);
                                    $('#med_'+med_id+'_numGostos').text(gostos+1);
                                }
                            },
                            error: function(){
                                alert('Ocorreu um erro.');
                            }
                        });
                    }
                    $(window).scroll(function(){
                        if (scrollLoad && ($(document).height() - $(window).height())-$(window).scrollTop()<=400){
                            scrollLoad = false;
                            carregarMedia(api_link);
                        }
                    });
                    </script>

                </div>";
            }
			?>
        </div>
	</body>
</html>