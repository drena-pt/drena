/*
JS - Gostar de médias

#med_XXXXXXXXX_gos      = Div de gostos
#med_XXXXXXXXX_gos_num  = Span com número de gostos
#med_XXXXXXXXX_gos_svg1 = SVG Gosto ativo
#med_XXXXXXXXX_gos_svg0 = SVG Gosto inativo
*/

function gosto(med_id, p){
    r = api('med_gos',{'med':med_id});
    //Padrão de ID's
    if (!p){
        p = "med_"+med_id+"_gos";
    }
    p = "#"+p;
    $(p+"_num").text(r.num);
    meds[med_id].gos = r.num;
    if (r.gos=='true'){
        $(p+"_svg1").removeAttr('hidden');
        $(p+"_svg0").attr('hidden', true);
        $(p).addClass('bg-opacity-50').removeClass('bg-opacity-25');
        meds[med_id].tem_gos = true;
    } else {
        $(p+"_svg1").attr('hidden', true);
        $(p+"_svg0").removeAttr('hidden');
        $(p).addClass('bg-opacity-25').removeClass('bg-opacity-50');
        meds[med_id].tem_gos = false;
    }
}