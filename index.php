<?php 
require('head.php');

if ($uti){
    echo "<script src='/js/api.min.js'></script>";
}
?>
    <meta name="description" content="Website de partilha de projetos, vídeo, música e imagens. Partilha o teu trabalho livremente na drena.">
    </head>
	<body>
		<?php require('header.php'); ?>
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
    
            <div class='p-0 my-3 col-xl-6 offset-xl-3'>
                <section id='section_med' class='mx-sm-0 mx-1 mw-sm-100 mw-auto row row-cols-2 row-cols-md-3'>
                ";
                if ($resultado = $bd->query("SELECT * FROM med WHERE pri=0 ORDER by den DESC LIMIT 12")) {
                    while ($campo = $resultado->fetch_assoc()) {
                        switch ($campo['tip']) {
                            case '1': $tip_icon='camera-video'; $tip_cor='primary'; break;
                            case '2': $tip_icon='soundwave'; $tip_cor='rosa'; break;
                            case '3': $tip_icon='image'; $tip_cor='ciano'; break;
                        }
                        echo '
                        <div class="col p-1 p-sm-2">
			            <div class="ratio ratio-4x3">
                        <a class="text-light text-decoration-none" href="/m/'.$campo['id'].'">
                            <div class="bg-rosa contentor_med h-100 rounded-xl d-flex" style="background-image:url('.$url_media.'thumb/'.$campo['thu'].'.jpg);">
                                <div class="rounded-bottom d-flex w-100 align-items-center align-self-end bg-dark bg-opacity-75 p-2">
                                    <span class="mx-1 text-'.$tip_cor.'"><i class="bi bi-'.$tip_icon.'"></i></span>
                                    <span class="overflow-hidden">'.encurtarNome($campo['tit']).'</span>
                                </div>
                            </div>
                        </a>
                        </div>
                        </div>';
                    } 
                    $resultado->free();
                }
                echo "
                </section>
            </div>";
            require "footer.php";
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
                <h2>"._('Ainda não tens conhecidos')."</h2>
                <span data-bs-toggle='modal' data-bs-target='#modal_procurar'><button class='btn btn-primary'>"._('Procurar')."<i class='bi bi-search'></i></button></span>
                </div>";
            }

            echo "
                <div class='my-4'>";
                if ($conhecidos){
                    if ($_GET['feed']=='global'){
                        $api_link = $url_site."api/feed.php?uti=".$uti['id']."&tip=global";
                        echo "<a href='/' class='btn btn-light' role='button'>"._('Feed')." <i class='bi bi-view-stacked'></i></a>
                        <a class='btn btn-primary' role='button'>"._('Feed global')." <i class='bi bi-globe'></i></a>";
                    } else {
                        $api_link = $url_site."api/feed.php?uti=".$uti['id'];
                        echo "<a class='btn btn-primary' role='button'>"._('Feed')." <i class='bi bi-view-stacked'></i></a>
                        <a href='/?feed=global' class='btn btn-light' role='button'>"._('Feed global')." <i class='bi bi-globe'></i></a>";
                    }
                } else {
                    $api_link = $url_site."/api/feed.php?tip=global";
                }
                echo "</div>";

                if ($_GET['feed']!='global' AND $conhecidos){
                    echo "
                    <div class='mx-0 mx-xl-2'>";
                    if ($resultado = $bd->query("SELECT * FROM uti WHERE id IN (".$lista_feed.") ORDER by id DESC LIMIT 8")){
                        while ($campo_uti = $resultado->fetch_assoc()){
                            echo "<a data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$campo_uti['nut']."' href='/u/".$campo_uti['nut']."'><img src='".$url_media."fpe/".$campo_uti['fpe'].".jpg' class='mx-1 rounded-circle' width='32'></a>";
                        }
                    }
                    echo "
                    </div>";
                }

                $append_med = "
                <section class='bg-dark bg-gradient shadow my-4'>
                    <div class='mw-100' id='med_\"+data[index].med.id+\"_conteudo'></div>
                    <div class='p-xl-5 p-4 text-start'>

                        <section class='row mb-3'>
                            <div class='col-auto pe-0'>
                                <a href='/u/\"+data[index].uti.nut+\"'><img src='\"+data[index].uti.fpe+\"' class='rounded-circle' width='40'></a>
                            </div>
                            
                            <div class='col'>
                                <text id='med_tit' class='h5'>\"+data[index].med.tit+\"</text><br>
                                <span>"._('Publicado por')." \"+data[index].uti.nut+\"</span>
                            </div>

                            <div class='col my-0 d-flex flex-row-reverse'>
                                <a href='/m/\"+data[index].med.id+\"' role='button' class='btn btn-light my-auto'>
                                    "._('Abrir')." <i class='bi bi-box-arrow-in-right'></i>
                                </a>
                            </div>
                        </section>

                        <section>
                            <span class='badge bg-primary py-1 pe-3' role='button' onclick='gosto(`\"+data[index].med.id+\"`)'  id='btn_gos_\"+data[index].med.id+\"'>
                                <span>
                                    <i id='svg_gos1_\"+data[index].med.id+\"' class='bi bi-hand-thumbs-up-fill'></i>
                                    <i id='svg_gos0_\"+data[index].med.id+\"' class='bi bi-hand-thumbs-up'></i>
                                </span>
                                <span id='med_\"+data[index].med.id+\"_numGostos'>\"+data[index].med.gos+\"</span>&nbsp;"._('gostos')."
                            </span>

                            <span class='badge bg-light bg-opacity-10 py-1 pe-3'>
                                <i class='bi bi-calendar4-week'></i>
                                \"+dayjs.tz(data[index].med.den, 'UTC').fromNow()+\"
                            </span>
                        </section>

                    </div>
                </section>
                ";

                echo "
                <div id='medias'></div>
                <script>
                let scrollLoad = true;
                let api_link = '".$api_link."';

                function carregarMedia(url){
                    $.get(url, function(data) {
                        if (data){
                            if (data.erro!=null){
                                console.log('ERRO: '+data.erro);
                            } else {
                                for(var index = 0; index < data.length; index++) {
                                    
                                    //Carrega a média apenas se não for repetida
                                    if (!$('#med_'+data[index].med.id+'_conteudo').length){
                                        $('#medias').append(\"".trim(preg_replace('/\s\s+/', ' ', $append_med))."\");
                                        
                                        if (data[index].uti.gos==1){
                                            $('#svg_gos0_'+data[index].med.id).attr('hidden', true);
                                            $('#btn_gos_'+data[index].med.id).addClass('bg-opacity-50');
                                        } else {
                                            $('#svg_gos1_'+data[index].med.id).attr('hidden', true);
                                            $('#btn_gos_'+data[index].med.id).addClass('bg-opacity-25');
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
					result = api('med_gos',{'med':med_id});
                    $('#med_'+med_id+'_numGostos').text(result.num);
                    if (result.gos=='true'){
                        $('#svg_gos1_'+med_id).removeAttr('hidden');
                        $('#svg_gos0_'+med_id).attr('hidden', true);
                        $('#btn_gos_'+med_id).addClass('bg-opacity-50').removeClass('bg-opacity-25');
                    } else {
                        $('#svg_gos1_'+med_id).attr('hidden', true);
                        $('#svg_gos0_'+med_id).removeAttr('hidden');
                        $('#btn_gos_'+med_id).addClass('bg-opacity-25').removeClass('bg-opacity-50');
                    }
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
	</body>
</html>