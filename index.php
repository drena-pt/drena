<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

require('head.php');

if ($uti){
    echo "
    <script src='/js/api.min.js'></script>
    <script src='/js/api/gos.js'></script>
    <script src='/js/api/med.js'></script>
    ";
}
?>
		<style>
        /*  PÁGINA INICIAL  */
		.jumbotron{
			height: 90vh;
			background-image: linear-gradient(-90deg,rgba(0,0,0,0.6),rgba(0,0,0,0),rgba(0,0,0,0.6)),url("imagens/fundo.jpg");
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
		}
		</style>

        <meta name="description" content="Website de partilha de projetos, vídeo, música e imagens. Partilha o teu trabalho livremente na drena.">
    </head>
	<body>
		<?php require('header.php'); ?>

		<?php
        /////////////////////////////  PÁGINA INICIAL  /////////////////////////////
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
                            <div class="bg-primary contentor_med h-100 rounded-xl d-flex" style="background-image:url('.$url_media.'thumb/'.$campo['thu'].'.jpg);">
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
            
        /////////////////////////////  PÁGINA INICIAL  //////////////////////////FIM
        } else {
        //////////////////////////////////  FEED  //////////////////////////////////



        ///////////////////////////////   COISAS PARA ÁLBUNS   ///////////////////////////////

        $append_alb = '
        <section id="alb_`+alb_id+`" class="bg-alb shadow my-4">
            <div class="mw-100">
                
                <div id="alb_`+alb_id+`_carrossel" class="carousel slide">
                    <div id="alb_`+alb_id+`_carrossel_inner" class="carousel-inner">
                    </div>

                    <button data-bs-target="#alb_`+alb_id+`_carrossel" data-bs-slide="prev" class="carousel-control-prev" type="button">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button data-bs-target="#alb_`+alb_id+`_carrossel" data-bs-slide="next" class="carousel-control-next" type="button">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Seguinte</span>
                    </button>

                </div>

                <section id="alb_`+alb_id+`_scroll" class="overflow-auto w-100 alb_scrollbar">
                    <div class="flex-row d-flex">
                        <span class="alb_seletor position-absolute start-50 translate-middle border-light border"></span>
                        <div style="flex: 0 0 auto;width: calc(50% - 36px);"></div>

                        <span id="alb_`+alb_id+`_thumb" class="flex-row d-flex">
                        </span>

                        <div style="flex: 0 0 auto;width: calc(50% - 36px);"></div>
                    </div>
                </section>

            </div>
            <div class="p-xl-5 p-4 pt-2 pt-xl-3 text-start">

                <section class="row mb-3">
                    <div class="col-auto pe-0">
                        <a id="alb_`+alb_id+`_uti_link">
                            <img id="alb_`+alb_id+`_uti_fpe" class="rounded-circle" width="40">
                        </a>
                    </div>
                    <div class="col">
                        <text id="alb_`+alb_id+`_med_tit" class="h5">X</text><br>
                        Publicado por <span id="alb_`+alb_id+`_uti_nut">X<span>
                    </div>
                </section>
                
                <section>
                    <span id="alb_`+alb_id+`_gos">
                    </span>
                    
                    <span class="badge bg-light bg-opacity-10 py-1 pe-3">
                        <i class="bi bi-calendar4-week"></i>
                        <text id="alb_`+alb_id+`_med_den">X</text>
                    </span>
                </section>
                
            </div>
        </section>
        ';

        //Interior do botão de gosto
        $append_med_gos = '
        <span>
            <i id="med_`+med.id+`_gos_svg1" class="bi bi-hand-thumbs-up-fill"></i>
            <i id="med_`+med.id+`_gos_svg0" class="bi bi-hand-thumbs-up"></i>
        </span>
        <span id="med_`+med.id+`_gos_num"></span> gostos
        ';
        //Botão de gosto completo, pois está encapsulado dentro de um Albúm
        //O botão é renderizado separadamente pois é interativo e padronizado para todos os posts
        $append_alb_gos = '<span id="med_`+med.id+`_gos" onclick="gosto(\'`+med.id+`\')" class="badge bg-primary py-1 pe-3" role="button">
            '.$append_med_gos.'</span>';



        $append_alb_carrossel = "
        <div class='carousel-item `+first_med+`'>
            <iframe style='min-height:50vh;' class='w-100' src='/embed?id=`+med_id+`'></iframe>
        </div>
        ";

        $append_alb_thumb = "
        <button class='btn shadow-none p-1' data-bs-target='#alb_`+alb_id+`_carrossel' data-bs-slide-to='`+num+`'>
            <img class='alb_thu' src='`+meds[med_id].thu+`'>
        </button>
        ";
        ?>
        <style>
        /* ÁLBUNS */

        /* Scrollbar dos Álbuns */
        .alb_scrollbar::-webkit-scrollbar {
            display: none;
        }
        .alb_scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .alb_scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            border: none;
        }
        /* Albúm carrossel */
        .alb_thu{
            width: 64px;
            height: 48px;
            object-fit: cover;
            border-radius: 8.5px;
        }
        .alb_seletor{
            width: 68px;
            height: 52px;
            margin-top: 28px;
            border-radius: 10px;
        }
        .bg-alb{
            background-image: linear-gradient(-70deg,#17161F,#161033);
        }
        </style>
        
        <?php
        ///////////////////////////////   COISAS PARA ÁLBUNS   ////////////////////////////FIM

            echo "<div class='p-0 col-xl-6 offset-xl-3 text-center'>";

            //Mensagem sobre último post do utilizador
            $uti_ultima_med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE uti='".$uti['id']."' ORDER by den DESC LIMIT 1;"));
            echo "<div class='mt-4' id='uti_ultima_med'></div>
            <script>
                const now = dayjs.tz();
                const ultima_med = dayjs.tz('".$uti_ultima_med['den']."');
                const ultima_med_diff = now.diff(ultima_med, 'day');
                if (ultima_med_diff > 5) {
                    ultima_med_tempo = dayjs.tz('".$uti_ultima_med['den']."', 'UTC').fromNow(true)
                    $('#uti_ultima_med').html(`"._('Não publicas nada desde há')." ` + ultima_med_tempo );
                }
            </script>
            ";

            //SQL: Verifica se o utilizador tem conhecidos para mostrar as opções de "Feed" e "Feed Global"
            $sql_conhecidos = "SELECT * FROM ami WHERE a_id='".$uti['id']."' AND sim='1' OR b_id='".$uti['id']."' AND sim='1' ORDER by b_dat DESC";
            $conhecidos = mysqli_fetch_assoc(mysqli_query($bd, $sql_conhecidos));
            if ($conhecidos){

                echo "<div class='mt-4'>";
                if ($_GET['feed']=='global'){
                    $feed_tip = 'global';
                    echo "<a href='/' class='btn btn-light' role='button'><i class='bi bi-view-list'></i>"._('Feed')."</a>
                    <a class='btn btn-primary' role='button'><i class='bi bi-globe'></i>"._('Feed global')."</a>";
                } else {
                    echo "<a class='btn btn-primary' role='button'><i class='bi bi-view-list'></i>"._('Feed')."</a>
                    <a href='/?feed=global' class='btn btn-light' role='button'><i class='bi bi-globe'></i>"._('Feed global')."</a>";
                }
                echo "</div>";

            } else {
                echo "
                <div class='alert d-flex align-items-center justify-content-between text-start border-0 bg-primary bg-opacity-10 mt-4' role='alert'>
                    <span>
                        <i class='bi bi-person-plus-fill'></i>
                        "._('Ainda não tens conhecidos')."
                    </span>
                    <span data-bs-toggle='modal' data-bs-target='#modal_procurar'><button class='btn btn-primary m-0'><i class='bi bi-search'></i> "._("Procurar utilizadores")."</button></span>
                </div>";

                $feed_tip = 'global';
            }

            $append_med = "
            <section class='bg-dark bg-gradient shadow my-4'>
                <div class='mw-100' id='med_\"+med.id+\"_conteudo'></div>
                <div class='p-xl-5 p-4 text-start'>

                    <section class='row mb-3'>
                        <div class='col-auto pe-0'>
                            <a href='/u/\"+med.uti.nut+\"'><img src='\"+med.uti.fpe+\"' class='rounded-circle' width='40'></a>
                        </div>
                        
                        <div class='col'>                            
                            <a id='med_tit' href='/m/\"+med.id+\"' class='h5 text-decoration-none text-light' data-bs-original-title='"._('Abrir')."' data-bs-toggle='tooltip' data-bs-placement='right'>\"+med.tit+\"</a><br>
                            <span>"._('Publicado por')." \"+med.uti.nut+\"</span>
                        </div>
                    </section>

                    <section>
                        <span id='med_\"+med.id+\"_gos' onclick='gosto(`\"+med.id+\"`)' class='badge bg-primary py-1 pe-3' role='button'>
                        </span>

                        <span class='badge bg-light bg-opacity-10 py-1'>
                            <i class='bi bi-calendar4-week'></i>
                            \"+dayjs.tz(med.den, 'UTC').fromNow()+\"
                        </span>
                    </section>

                </div>
            </section>
            ";
            ?>
            <div id='feed'></div>

            <script>
            //ÁLBUNS

            //Variáveis e constantes
            const alb_thu_width = 72;
            var alb = [];
            var alb_carrossel = [];

            function carregar_alb(alb_id){
                
                //Request à API
                api_alb = api('ob_med',{'alb':alb_id});
                if (!api_alb.err){

                    //Guarda as informações do álbum localmente
                    alb[alb_id] = api_alb;

                    //Append do Álbum no feed
                    $("#feed").append(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_alb)); ?>`);
                    //Cria carrossel
                    alb_carrossel[alb_id] = new bootstrap.Carousel("#alb_"+alb_id+"_carrossel");
                
                    first_med='active';

                    //Carrega cada média a partir do output da api (api_alb)
                    $.each(api_alb.meds, function (num, med) {
                        //Adiciona a média à array meds[]
                        med_id = med.id;
                        meds[med_id] = med;
                        //Limpa a informação da média no alb[] e apenas deixa o ID
                        alb[alb_id].meds[num] = med_id;

                        $("#alb_"+alb_id+"_carrossel_inner").append(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_alb_carrossel)); ?>`);
                        $("#alb_"+alb_id+"_thumb").append(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_alb_thumb)); ?>`);
                        first_med='';
                    });

                    //Renderiza a info da primeira média do carrossel
                    alb_med_info(0,alb_id);
                } else {
                    $("#alb_"+alb_id).html("<text class='h4'>Álbum não encontrado</text>");
                }

                console.debug(alb);
                
            }

            //Atualiza a informação no álbum sobre a média atual
            function alb_med_info(num,alb_id){
                med_id = alb[alb_id].meds[num];
                med = meds[med_id];

                //PARTES: Estáticas
                $("#alb_"+alb_id+"_uti_nut").html(alb[alb_id].uti.nut);
                $("#alb_"+alb_id+"_uti_fpe").attr('src', alb[alb_id].uti.fpe);
                $("#alb_"+alb_id+"_uti_link").attr('href', '/u/'+alb[alb_id].uti.nut);
                $("#alb_"+alb_id+"_med_tit").html(med.tit);
                $("#alb_"+alb_id+"_med_den").html(dayjs.tz(med.den,'UTC').fromNow());

                //PARTES: Interativas

                //Renderiza o botão de gosto
                $("#alb_"+alb_id+"_gos").html(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_alb_gos)); ?>`);
                //Atualiza o estado do botão de gosto
                gosto_estado(med.id);
            }

            function alb_scroll(num_med, alb_id) {
                scroll_point = num_med * alb_thu_width;
                console.debug("Scrolling "+alb_id+":"+scroll_point);
                $("#alb_"+alb_id+"_scroll").stop().animate({
                    scrollLeft: scroll_point
                }, 300);
            }
        
            $('body').on('slide.bs.carousel', '.carousel', event => {
                elementId = event.target.id;
                console.log(elementId);

                alb_id = elementId.replace('alb_', '');
                alb_id = alb_id.replace('_carrossel', '');
                alb_slide_num = event.to;

                alb_scroll(alb_slide_num, alb_id);
                alb_med_info(alb_slide_num,alb_id);
            });
            
            $(document).ready(function() {
                var scrollTimer = null;
                function attachScrollHandler() {
                    $('.alb_scrollbar:not(.scroll-handler-attached)').on('scroll', function(e) {
                        este = $(this);
                if(scrollTimer!==null){clearTimeout(scrollTimer);}
                scrollTimer = setTimeout(function() {
                    position = $(este).scrollLeft();
                    closest = Math.round(position/alb_thu_width);
                    console.debug("Indo para: "+position+"px = "+closest);
                    alb_scroll(closest);

                    elementId = este[0].id;
                    alb_id = elementId.replace('alb_', '');
                    alb_id = alb_id.replace('_scroll', '');

                    alb_carrossel[alb_id].to(closest);
                }, 150);
                    }).addClass('scroll-handler-attached');
                }
                attachScrollHandler();
                setInterval(function() { attachScrollHandler(); }, 1000);
            });
            
            </script>

            <script>
            //FEED
            let scrollLoad = true;
            var feed_tip = '<?php echo $feed_tip ?>';
            var feed_depois;
            function feed(){
                api_feed = api('feed2',{'tip':feed_tip,'depois':feed_depois});
                if (!api_feed.err){
                    for(var i = 0; i < api_feed.length; i++) {

                        obj = api_feed[i];

                        //Vê que tipo de objeto é
                        if (obj.tip=='med'){

                            med = obj;
                            //Adiciona a média à array meds[]
                            meds[med.id] = med;

                            //Carrega a média
                            $('#feed').append("<?php echo trim(preg_replace('/\s\s+/', ' ', $append_med)); ?>");
                            //Adiciona div do gosto #################################TENTAR DAR MERGE COM O CODIGO DO ALBUM PARA POUPAR CODIGO
                            $("#med_"+med.id+"_gos").html(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_med_gos)); ?>`);
                            //Atualiza o estado do botão de gosto
                            gosto_estado(med.id);

                            if (med.tip==1){
                                $('#med_'+med.id+'_conteudo').html("<div style='position:relative;padding-bottom:56.25%;'><iframe style='position:absolute;top:0;left:0;width:100%;height:100%;' src='/embed?id="+med.id+"&titulo=0'></iframe></div>");
                            } else if (med.tip==2){
                                $('#med_'+med.id+'_conteudo').html("<iframe height='180px' class='w-100' src='/embed?id="+med.id+"&titulo=0'></iframe>");
                            } else {
                                $('#med_'+med.id+'_conteudo').html("<iframe style='min-height:50vh;' class='w-100' src='/embed?id="+med.id+"&titulo=0'></iframe>");
                            }

                        } else if (obj.tip=='depois'){
                            feed_depois = obj.med;
                            continue;
                        } else if (obj.tip=='alb'){
                            carregar_alb(obj.id);
                            continue;
                        }

                    }
                }
                scrollLoad = true;
            }
            //Carrega o feed pela primeira vez ao entrar na página
            feed();
            //Carrega o feed quando dá scroll até ao fundo
            $(window).scroll(function(){
                if (scrollLoad && ($(document).height() - $(window).height())-$(window).scrollTop()<=400){
                    scrollLoad = false;
                    feed();
                }
            });
            </script>

            </div>

        <?php
        //////////////////////////////////  FEED  ///////////////////////////////FIM
        }
		?>
	</body>
</html>