<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */

# Funções
require 'fun.php';

$sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='".$_GET["sec"]."'"));          # Informações da secção

if ($sec){ # Se a secção existir
    if ($_GET['ac']=='editar'){

        $sec_pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".$sec["pro"]."'"));       # Informações do projeto baseado na secção
        $sec_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$sec_pro["uti"]."'"));      # Informações do utilizador baseado no projeto
    
        if ($sec_uti['id']!=$uti['id']){ # Se o utilizador não for dono do projeto.
            echo "Erro: Não podes editar esta secção.";
            exit;
        } else {
            echo "
            <div class='bg-light text-dark mb-4'>
                <div id='editorjs_".$sec['id']."'></div>
            </div>
            <div class='text-right'>
                <button class='btn btn-light' data-toggle='tooltip' data-placement='bottom' data-original-title='Guardar' onclick=\"guardar()\">
                    Guardar	<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#save'/></svg>
                </button>
            </div>

            <script>
                const editor_".$sec['id']." = new EditorJS({
                    ";
                    if ($sec['tex']){
                        echo "data: ".($sec['tex']).",";
                    }
                    echo "
                    holder: 'editorjs_".$sec['id']."',
                    logLevel: 'ERROR',
                    tools: {
                        header: {
                            class: Header,
                            config: {
                                placeholder: 'Enter a header',
                                //levels: [2, 3, 4],
                                levels: [3],
                                defaultLevel: 3
                            }
                        },
                        embed: {
                            class: Embed,
                            config: {
                                services: {
                                    youtube: {
                                        embedUrl: 'https://www.youtube-nocookie.com/embed/<%= remote_id %>'
                                    },
                                    drena: {
                                        regex: /https:\/\/drena.xyz\/media\?id=([^\/\?\&]*)/,
                                        embedUrl: 'https://drena.xyz/embed?id=<%= remote_id %>',
                                        html: \"<iframe style='height:340px;' scrolling='no' frameborder='no' allowtransparency='true' allowfullscreen='true'></iframe>\"
                                    }
                                }
                            }
                        },
                    }
                });

                function guardar(){
                    editor_".$sec['id'].".save().then((outputData) => {
                        $.post('pro/sec.php?sec=".base64_encode($sec['id'])."&ac=guardar',{
                            texto: JSON.stringify(outputData)
                        },
                        function(data){
                            alert('Guardado com suecesso!');
                        });
                    }).catch((error) => {
                    console.log('Saving failed: ', error)
                    });
                }
            </script>
            ";
        }
    } else {
        echo "Erro: Nenhuma ação selecionada!";
    }
} else {
    echo "Erro: A seccção não existe!";
}
exit;
?>