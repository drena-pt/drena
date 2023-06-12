<?php 
require('head.php');
?>
    <script src='/js/api.min.js'></script>
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

    /* Albúm Carrosel */
    .alb_thu{
        width: 64px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
    }
    .alb_seletor{
        width: 67px;
        height: 51px;
        margin-top: 28px;
    }
</style>
</head>

<body>
    <?php require('cabeçalho.php'); ?>

    <div class="p-0 col-xl-6 offset-xl-3 text-center">
        <section class="bg-dark bg-gradient shadow my-4">
            <div class="mw-100">
                
                <div id="alb_carrosel" class="carousel slide">
                    <div id="alb_carrosel_inner" class="carousel-inner">
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#alb_carrosel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#alb_carrosel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                </div>

                <section id="alb_scroll" class="overflow-auto w-100 scrollbar">
                    <div class="flex-row d-flex">
                        <span class="alb_seletor position-absolute start-50 translate-middle rounded border-light border"></span>
                        <div style="flex: 0 0 auto;width: calc(50% - 36px);"></div>

                        <span id="alb_thumb" class="flex-row d-flex">
                        </span>

                        <div style="flex: 0 0 auto;width: calc(50% - 36px);"></div>
                    </div>
                </section>


            </div>
            <div class="p-xl-5 p-4 pt-2 pt-xl-3">
                <div class="row mb-3">
                    <div class="col d-flex">
                        <text class="h5 my-auto text-start" id="med_tit">X</text>
                    </div>
                </div>
                <section class="mt-auto">
                    <div class="row mb-1">
                        <div class="col-auto pe-0 text-center">
                            <a href="/u/guilhae">
                                <img src="https://testes.gigadrena.pt/uploads/fpe/8Tbf1Sg4.jpg" class="rounded-circle" width="40">
                            </a>
                        </div>
                        <div class="col d-flex">
                            <span class="justify-content-center align-self-center">Publicado por guilhae</span>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-auto pe-0 text-center">
                            <span id="alb_med_gosto" role="button">
                                <i id="alb_med_svg_gostei" class="bi bi-hand-thumbs-up-fill" hidden="hidden"></i>
                                <i id="alb_med_svg_gosto" class="bi bi-hand-thumbs-up"></i>
                            </span>
                        </div>
                        <div class="col d-flex">
                            <span id="med_gos_num">X</span>&nbsp;gostos
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-auto pe-0 text-center"><i class="bi bi-calendar4-week"></i></div>
                        <div id="med_den" class="col d-flex">X</div>
                    </div>
                </section>
            </div>
        </section>
    </div>

    <?php

    $append_carrosel = "
    <div class='carousel-item \"+first_med+\"'>
        <iframe style='min-height:50vh;' class='w-100' src='/embed?id=\"+med.id+\"'></iframe>
    </div>
    ";

    $append_thumb = "
    <button class='btn p-1' data-bs-target='#alb_carrosel' data-bs-slide-to='\"+id+\"'>
        <img class='alb_thu' src='\"+med.thu+\"'>
    </button>
    ";

    ?>

    <script>
        //Albúm feed script

        var alb = [];
        var alb_id = '2';
        function carregar_alb(alb_id){
			result = api('ob_med',{'alb':alb_id});
			if (!result['err']){
                alb[alb_id] = result;
                first_med='active';
				$.each(result, function (id, med) {
                    $("#alb_carrosel_inner").append("<?php echo trim(preg_replace('/\s\s+/', ' ', $append_carrosel)); ?>");
                    $("#alb_thumb").append("<?php echo trim(preg_replace('/\s\s+/', ' ', $append_thumb)); ?>");
                    first_med='';
				})
			}
		}
		carregar_alb(alb_id);
        console.debug(alb);

        const alb_thu_width = 72;
        const alb_carousel = new bootstrap.Carousel('#alb_carrosel');

        $('.carousel').on('slide.bs.carousel', event => {
            const alb_slide_num = event.to;
            alb_scroll(alb_slide_num);
            alb_med_info(alb_slide_num);
        });
        function alb_scroll(id_media) {
            scroll_point = id_media * alb_thu_width;
            $("#alb_scroll").stop().animate({
                scrollLeft: scroll_point
            }, 300);
        }
        function alb_med_info(id){
            $("#med_tit").html(alb[alb_id][id].tit);
            $("#med_gos_num").html(alb[alb_id][id].gos);
            $("#med_den").html(dayjs.tz(alb[alb_id][id].den,'UTC').fromNow());
            $("#alb_med_gosto").attr("onclick","gosto('"+alb[alb_id][id].id+"')");
            //Verificar se tem gosto
            if (alb[alb_id][id].tem_gos==1){
                $("#alb_med_svg_gosto").attr('hidden', true);
                $("#alb_med_svg_gostei").removeAttr('hidden');
            } else {
                $("#alb_med_svg_gosto").removeAttr('hidden');
                $("#alb_med_svg_gostei").attr('hidden', true);
            }
        }
        $("#alb_scroll").scroll(function () {
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                position = $("#alb_scroll").scrollLeft();
                closest = Math.round(position/alb_thu_width);
                console.debug("Indo para: "+position+"px = "+closest);
                alb_scroll(closest);
                alb_carousel.to(closest);
            }, 300));
        });

        //Quando inicia pela primeira vez carrega o primeiro slide
        alb_med_info(0);
    </script>

</body>

</html>