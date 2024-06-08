<?php 
require('head.php');
?>
    <script src='/js/api.min.js'></script>
    <?php
    #Carrega o script de gosto dependendo se o utilizador está logado
    if (!$uti){
        echo "<script> function gosto(){ window.open('/entrar','_self'); } </script>";
    } else {
        echo "<script src='/js/api/gos.js'></script>";
    }
    ?>
    <style>
        /* Scrollbar */
        .scrollbar::-webkit-scrollbar {
            /* width: 6px;
                    height: 6px; */
            display: none;
        }

        .scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar::-webkit-scrollbar-thumb {
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
            width: 67px;
            height: 51px;
            margin-top: 28px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <?php require('header.php'); ?>

    <div class="p-0 col-xl-6 offset-xl-3 text-center" id="feed">

    <?php
    $alb = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_alb WHERE id='".$_GET["id"]."'"));

    if ($alb['uti']!=$uti['id']){
        $global_hide = "d-none"; ####################################################### Mudar o nome da variavel
    }
    
    $append_alb = '
    <section id="alb_`+alb_id+`" class="bg-dark bg-gradient shadow my-4">
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

            <section id="alb_`+alb_id+`_scroll" class="overflow-auto w-100 scrollbar">
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
                <div class="col my-0 d-flex flex-row-reverse '.$global_hide.'">
                    <a href="/album?id=`+alb_id+`" role="button" class="btn btn-light me-1 my-auto">
                        '._("Editar").'<i class="bi bi-pencil-square"></i>
                    </a>
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

    //O botão é renderizado separadamente pois é interativo e padronizado para todos os posts
    $append_alb_gos = '
    <span id="med_`+med_id+`_gos" onclick="gosto(\'`+med_id+`\')" class="badge bg-primary bg-opacity-50 py-1 pe-3" role="button">
        <span>
            <i id="med_`+med_id+`_gos_svg1" class="bi bi-hand-thumbs-up-fill"></i>
            <i id="med_`+med_id+`_gos_svg0" class="bi bi-hand-thumbs-up"></i>
        </span>
        <span id="med_`+med_id+`_gos_num">`+med.gos+`</span> gostos
    </span>
    ';
    
    $append_carrossel = "
    <div class='carousel-item `+first_med+`'>
        <iframe style='min-height:50vh;' class='w-100' src='/embed?id=`+id+`'></iframe>
    </div>
    ";

    $append_thumb = "
    <button class='btn p-1' data-bs-target='#alb_`+alb_id+`_carrossel' data-bs-slide-to='`+num+`'>
        <img class='alb_thu' src='`+meds[id].thu+`'>
    </button>
    ";

    ?>

    </div>

    <script>
        //Albúm feed script

        //CONSTANTES
        const alb_thu_width = 72;
        
        var alb = [];
        var alb_carrossel = [];
        var meds = []; //Médias carregadas no browser

        function carregar_alb(alb_id){
            $("#feed").append(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_alb)); ?>`);

            //Cria carrossel
            alb_carrossel[alb_id] = new bootstrap.Carousel("#alb_"+alb_id+"_carrossel");

			api_alb = api('ob_med',{'alb':alb_id});
			if (!api_alb.err){
                alb[alb_id] = api_alb;
                first_med='active';

				$.each(api_alb.meds, function (num, med) {
                    //Adiciona ao objeto de médias
                    id = med.id;
                    meds[id] = med;
                    alb[alb_id].meds[num] = id;

                    $("#alb_"+alb_id+"_carrossel_inner").append(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_carrossel)); ?>`);
                    $("#alb_"+alb_id+"_thumb").append(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_thumb)); ?>`);
                    first_med='';
				});

                //Renderiza a info da primeira média do carrossel
                med_info(0,alb_id);
			} else {
                $("#alb_"+alb_id).html("<text class='h4'>Álbum não encontrado</text>");
            }

            console.debug(alb);
            console.debug(meds);

            
		}

        function med_info(num,alb_id){
            med_id = alb[alb_id].meds[num];
            med = meds[med_id];

            //Partes estáticas
            $("#alb_"+alb_id+"_uti_nut").html(alb[alb_id].uti.nut);
            $("#alb_"+alb_id+"_uti_fpe").attr('src', alb[alb_id].uti.fpe);
            $("#alb_"+alb_id+"_uti_link").attr('href', '/u/'+alb[alb_id].uti.nut);
            $("#alb_"+alb_id+"_med_tit").html(med.tit);
            $("#alb_"+alb_id+"_med_den").html(dayjs.tz(med.den,'UTC').fromNow());

            //Partes interativas
            $("#alb_"+alb_id+"_gos").html(`<?php echo trim(preg_replace('/\s\s+/', ' ', $append_alb_gos)); ?>`);
                //Verificar se tem gosto
                if (med.meu_gos==1){
                    $("#med_"+med_id+"_gos_svg1").removeAttr('hidden');
                    $("#med_"+med_id+"_gos_svg0").attr('hidden', true);
                    $("#med_"+med_id+"_gos").addClass('bg-opacity-50').removeClass('bg-opacity-25');
                } else {
                    $("#med_"+med_id+"_gos_svg1").attr('hidden', true);
                    $("#med_"+med_id+"_gos_svg0").removeAttr('hidden');
                    $("#med_"+med_id+"_gos").addClass('bg-opacity-25').removeClass('bg-opacity-50');
                }
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
            med_info(alb_slide_num,alb_id);
        });


        //CARREGA O ALBUM DO ID
        carregar_alb('<?php echo $_GET["id"]; ?>');
        //carregar_alb('qYxbMM');
        
        $(document).ready(function() {
            var scrollTimer = null;
            function attachScrollHandler() {
                $('.scrollbar:not(.scroll-handler-attached)').on('scroll', function(e) {
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

</body>

</html>