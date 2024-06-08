/*
JS - Gostar de médias
(Dependente de: med.js)

#med_XXXXXXXXX_gos      = Div de gostos
#med_XXXXXXXXX_gos_num  = Span com número de gostos
#med_XXXXXXXXX_gos_svg1 = SVG Gosto ativo
#med_XXXXXXXXX_gos_svg0 = SVG Gosto inativo
*/


//gosto: Ativado ao clicar no botão de gosto, Faz request à API
function gosto(med_id, p){
    r = api('med_gos',{'med':med_id});
    //Padrão de ID's
    if (!p){
        p = "med_"+med_id+"_gos";
    }
    p = "#"+p;

    meds[med_id].gos = r.num;       //Atualiza o número de gostos na variável local
    meds[med_id].meu_gos = r.gos;   //Atualiza o estado do gosto do utilizador na variável local (1 ou 0)

    gosto_estado(med_id);
}

//gosto_estado: Atualiza o aspeto e informação do botão de gosto baseado nas variáveis locais
function gosto_estado(med_id){
    //Padrão de ID's
    p = "#med_"+med_id+"_gos";

    med = meds[med_id];

    $(p+"_num").text(med.gos);    //Escreve o número de gostos
    //Renderiza as cores e icones do botão
    if (med.meu_gos=='1'){
        $(p+"_svg1").removeAttr('hidden');
        $(p+"_svg0").attr('hidden', true);
        $(p).addClass('bg-opacity-50').removeClass('bg-opacity-25');
    } else {
        $(p+"_svg1").attr('hidden', true);
        $(p+"_svg0").removeAttr('hidden');
        $(p).addClass('bg-opacity-25').removeClass('bg-opacity-50');
    }
}