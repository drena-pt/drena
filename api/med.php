<?php
#API - Médias (Alterar o título/privacidade, Eliminar, Moderar, Comprimir vídeos)
#Composer, Header json, Ligação bd, Vaildar Token JWT, Utilizador
require_once('validar.php');

$ac = $_POST['ac']; #Ação

#Informações da media
$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_POST['med']."'"));

if ($med){ #Se a média existir

    $med_ext = end(explode(".",$med['nom'])); #Extensão do ficheiro

    if ($med['uti']==$uti['id']){ #Se for o dono da média
        
        if ($ac=='eliminar'){ #Se a ação for eliminar
        
            goto eliminar;

        } else if ($ac=='titulo'){ #Alterar título da média
            
            if ($_POST['tit']){
                if ($bd->query("UPDATE med SET tit='".addslashes($_POST['tit'])."' WHERE id='".$med['id']."'") === FALSE) {
                    echo '{"err": "'.$bd->error.'"}';
                }
                echo '{"est": "sucesso"}';
            } else {
                echo '{"err": "Título inválido."}';
            }
            exit;

        } else if ($ac=='privar'){ #Alterar privacidade

            if ($med['pri']=='1'){
                if ($bd->query("UPDATE med SET pri='0' WHERE id='".$med['id']."'") === FALSE) {
                    echo '{"err": "'.$bd->error.'"}';
                } else {
                    echo '{"est": "publico"}';
                }
            } else {
                if ($bd->query("UPDATE med SET pri='1' WHERE id='".$med['id']."'") === FALSE) {
                    echo '{"err": "'.$bd->error.'"}';
                } else {
                    echo '{"est": "privado"}';
                }
            }
            exit;

        } else if ($ac=='comprimir'){ # Alterar título da média
            
            if ($med['tip']=='1' AND $med['est']=='1'){ # Se a media for um vídeo e o estado for 1 (bitrate alto)
                $cmd = "php ".$dir_site."pro/med_compressao.php ".$med['id']." > /dev/null &";
                exec($cmd);
                sleep(2);
                echo '{"est": "sucesso"}';
            } else {
                echo '{"err": "Média inválida."}';
            }
            exit;

        } else {
            echo '{"err": "Nenhuma ação selecionada."}';
        }
        exit;

    } else if ($uti['car']==2) { #Se o utilizador for moderador

        if ($ac=='mod'){ #Se a ação for moderar

            #Registos da ultima moderação feita pelo utilizador:
            #Nivel 0 (Reverter ação)
            $med_mod_uti0 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='0';"));
            #Nivel 1 (Inapropriado)
            $med_mod_uti1 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='1';"));
            #Nivel 2 (Inaceitavel)
            $med_mod_uti2 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND uti='".$uti['id']."' AND niv='2';"));
            
            #Número de vezes em que uma mídia foi definida como inapropriada
            $med_mod_1 = mysqli_num_rows(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND niv='1';"));
            
            $ac_mod = $_POST['mod']; #Ação
            if (!isset($ac_mod)){
                #Ação não especificada, define o nível de moderação com o atual na base de dados
                $nmo = $med['nmo'];
            } else if ($ac_mod==0 AND !$med_mod_uti0){ #Caso seja 0 (Reverter a ação tomada) e o moderador nunca tenha tomado essa ação.

                if ($med['nmo']!=3 AND $med['nmo']<=4 ){ #Se o nível for 1,2 ou 4 retira 1
                    $nmo = $med['nmo']-1;
                } else if ($med['nmo']==3){ #Se o nível for 3
                    if ($med_mod_1>=2){ #reverte para 2
                        $nmo = 2;
                    } else { #reverte para 0
                        $nmo = 0;
                    }
                } else {
                    $mod_erro = 1;
                }

            } else if ($ac_mod==1 AND !$med_mod_uti1){ #Caso seja 1 (Definir como sensivel) e o moderador nunca tenha tomado essa ação.

                if ($med['nmo']==0){ #Se o nível for 0 define para 1
                    $nmo = 1;
                } else if ($med['nmo']==1){ #Se o nível for 1 define para 2
                    $nmo = 2;
                } else {
                    $mod_erro = 1;
                }

            } else if ($ac_mod==2 AND !$med_mod_uti2){ #Caso seja 2 (Definir como inaceitavel) e o moderador nunca tenha tomado essa ação.

                if ($med['nmo']==0 OR $med['nmo']==1 OR $med['nmo']==2){ #Se o nível for 0, 1 ou 2 define para 3
                    $nmo = 3;
                } else if ($med['nmo']==3){ #Se o nível for 3 define para 4
                    $nmo = 4;
                } else if ($med['nmo']==4){ #Se o nível for 4 rip
                    goto eliminar;
                    exit;
                } else {
                    $mod_erro = 1;
                }

            } else {
                $mod_erro = 1;
            }

            if ($mod_erro){ #Se houver erro não regista o evento
                echo '{"err": "Não foi possivel executar a moderação ('.$ac_mod.')."}';
            } else {
                if (isset($ac_mod)){ #Se tiver havido uma ação
                    #Atualiza o nível de moderação
                    $bd->query("UPDATE med SET nmo='".$nmo."' WHERE id='".$med["id"]."';");
                    #Cria registo da moderação
                    $bd->query("INSERT INTO med_mod (med, niv, uti) VALUES ('".$med['id']."', '".$ac_mod."', '".$uti['id']."')");
                }

                #Registo do ultimo voto
                $u_mod = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_mod WHERE med='".$med["id"]."' AND niv>'0' ORDER BY dre DESC;"));
                #Registo do moderador do ultimo voto
                $u_mod_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$u_mod['uti']."'"));

                #Ações de moderação tomadas
                $arr_ac_mod = [];
                if ($med_mod_uti0 OR $ac_mod=='0'){array_push($arr_ac_mod, 0);}
                if ($med_mod_uti1 OR $ac_mod==1){array_push($arr_ac_mod, 1);}
                if ($med_mod_uti2 OR $ac_mod==2){array_push($arr_ac_mod, 2);}
                #Sucesso, Ações de moderação tomadas pelo utilizador, Nivel atual de moderação da média.
                echo '{"est":"sucesso", "ac_mod":'.json_encode($arr_ac_mod).', "nmo":'.$nmo.', "u_mod_uti":{"nut":"'.$u_mod_uti['nut'].'", "fpe":"'.$url_media.'fpe/'.$u_mod_uti['fpe'].'.jpg"}}';
            }

        } else {
            echo '{"err": "Nenhuma ação selecionada."}';
        }

    } else { # Se não for o dono da média
        echo '{"err": "Não és o dono da média."}';
    }

} else { #Se a média não existir
    echo '{"err": "A média não foi encontrada."}';
}

exit;


#Função eliminar
eliminar:

if ($med['est']=='2'){ # Se o estado da média for 2 (processando)
    echo '{"err": "Não podes eliminar a média enquanto está a ser processada."}';
    exit;
}
$caminho_ori = $dir_media.'ori/'.$med['id'].'.'.$med_ext;
unlink($caminho_ori);        #Original
$caminho_som = $dir_media.'som/'.$med['id'].'.'.$med_ext;
unlink($caminho_som);        #Som
$caminho_img = $dir_media.'img/'.$med['id'].'.'.$med_ext;
unlink($caminho_img);        #Imagem
$caminho_comprimido = $dir_media.'comp/'.$med['id'].'.mp4';
unlink($caminho_comprimido); #Comprimido
$caminho_convertido = $dir_media.'conv/'.$med['id'].'.mp4';
unlink($caminho_convertido); #Convertido
$caminho_thumb = $dir_media.'thumb/'.$med['thu'].'.jpg';
unlink($caminho_thumb);      #Thumb

# Se existir algum dos ficheiros que supostamente foram apagados
if (file_exists($caminho_ori) OR file_exists($caminho_som) OR file_exists($caminho_img) OR file_exists($caminho_comprimido) OR file_exists($caminho_convertido) OR file_exists($caminho_thumb)){
    echo '{"err": "Não foi possivel remover os ficheiros."}';
} else if ($bd->query("DELETE FROM med_com WHERE med='".$med['id']."'") === FALSE) {
    echo '{"err": "'.$bd->error.'"}';
} else if ($bd->query("DELETE FROM med_gos WHERE med='".$med['id']."'") === FALSE) {
    echo '{"err": "'.$bd->error.'"}';
} else if ($bd->query("DELETE FROM med WHERE id='".$med['id']."'") === FALSE) {
    echo '{"err": "'.$bd->error.'"}';
} else {
    echo '{"est": "eliminado"}'; #Sucesso
}
exit;
?>