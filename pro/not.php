<?php
#Processo - Notificações
error_reporting(E_ALL);
ini_set('display_errors', 'On');

#Funções
require_once(__DIR__."/fun.php");
#Composer
require_once(__DIR__."/../vendor/autoload.php");
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function notificacao($uti_a_id, $uti_b_id, $tipo, $med_id = null, $extra = null){

    global $bd;
    global $url_media;
    global $url_site;

    #Se A e B forem o mesmo utilizador (não envia para ele mesmo né)
    #Mas envia caso seja uma notificação de compressão do vídeo!
    if ($uti_a_id==$uti_b_id AND $tipo!='processado'){
        return '{"est": "Notificação não enviada, pois o utilizador é o mesmo"}';
    }

    #Informações do utilizador A
    $uti_a = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$uti_a_id."';"));
    #Informações do utilizador B
    $uti_b = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$uti_b_id."';"));

    #Se o utilizador B tiver as notificações desativadas (rno = Receber Notificações)
    if ($uti_b['rno']!='1'){
        return '{"est": "Utilizador B desativou as notificações"}';
    }

    #Subscrições de notificação do utilizador B
    $sql_sub_uti_b = "SELECT * FROM not_sub WHERE uti='".$uti_b['id']."'";
    #Se houver alguma subscrição
    if(mysqli_num_rows(mysqli_query($bd, $sql_sub_uti_b)) > 0){

        #Informações da média
        if ($med_id){
            $med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$med_id."';"));
    
            $not_image = $url_media.'thumb/'.$med['thu'].'.jpg';
            $not_action = $url_site."m/".$med['id'];
        }

        #Escolhe o tipo de notificação
        switch ($tipo) {
            case 'gos': #Notificação de gosto
                $not_body = $med['tit'];

                #Define o título da notificação consoante o tipo de média
                switch ($med['tip']) {
                    case '1':
                        $not_title = sprintf(_('%s gostou do teu vídeo'),$uti_a['nut']);
                        break;
                    case '2':
                        $not_title = sprintf(_('%s gostou do teu áudio'),$uti_a['nut']);
                        break;
                    case '3':
                        $not_title = sprintf(_('%s gostou da tua imagem'),$uti_a['nut']);
                        break;
                    default:
					    $not_title = sprintf(_('%s gostou da tua publicação'),$uti_a['nut']);
                        break;
                }
                break;

            case 'com': #Notificação de comentário

                #Informações do comentário ($extra) é o id do comentário
                $com = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_com WHERE id='".$extra."';"));

                $not_body = $com['tex'];
    
                #Define o título da notificação consoante o tipo de média
                switch ($med['tip']) {
                    case '1':
                        $not_title = sprintf(_('%s comentou o teu vídeo'),$uti_a['nut']);
                        break;
                    case '2':
                        $not_title = sprintf(_('%s comentou o teu áudio'),$uti_a['nut']);
                        break;
                    case '3':
                        $not_title = sprintf(_('%s comentou a tua imagem'),$uti_a['nut']);
                        break;
                    default:
                        $not_title = sprintf(_('%s comentou a tua publicação'),$uti_a['nut']);
                        break;
                }
                break;
            
            case 'processado': #Notificação de comentário

                $not_body = $med['tit'].'\nConversão finalizada com sucesso!';
                $not_title = 'Vídeo processado';
                break;

            case 'ami_pedido': #Pedido de amizade

                $not_body = $uti_a['nut'].' quer ser teu conhecido';
                $not_title = 'Pedido de '.$uti_a['nut'];
                $not_action = $url_site."u/".$uti_a['nut'];
                break;

            case 'ami_aceite': #Pedido de amizade aceite

                $not_body = $uti_a['nut'].' agora é teu conhecido';
                $not_title = 'Pedido aceite';
                $not_action = $url_site."u/".$uti_a['nut'];
                break;

            default:
                return '{"err": "Tipo de notificação inválido"}';
                break;
        }

        $not_icon = $url_media."fpe/".$uti_a['fpe'].".jpg";

        $mensagem = '{
            "title":"'.$not_title.'",
            "options": 
                {
                    "icon":"'.$not_icon.'",
                    "body": "'.$not_body.'",
                    "image":"'.$not_image.'",
                    "badge": "'.$url_site.'imagens/favicon.png",
                    "vibrate": [300, 300, 300, 300, 300],
                    "actions": [
                        {
                        "action": "'.$not_action.'",
                        "title": "Ver"
                        }
                    ]
                }
            
        }';

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:contacto@drena.pt',
                'publicKey' => 'BPPvOxxaLpZ9EWAWALLfZUhmOQv-6jXDCVnt8yat4n4bcdvVJ1n0n1gHPa3WNw_P4W5lS_J5E0THSinXYo2yyVk',
                'privateKey' => 'bsTKoMpvyK6AWatNIRwiP4S1kuFpIZHUe5TjcPTcCkA',
            ],
        ];
        $webPush = new WebPush($auth);

        if ($resultado = $bd->query($sql_sub_uti_b)) {
            while ($campo = $resultado->fetch_assoc()){
                
                $notification = [
                    'subscription' => Subscription::create(json_decode($campo['sub'], true)),
                    'payload' => $mensagem,
                ];

                $report = $webPush->sendOneNotification(
                    $notification['subscription'],
                    $notification['payload']
                );
                
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    #Do nothing
                    #return '{"est": "sucesso"}';
                } else {
                    #echo '{"est": "Message failed to sent for subscription ('.$endpoint.')"}';
                    #SQL - Elimina a subscrição
                    if ($bd->query("DELETE FROM not_sub WHERE id=".$campo['id'].";") === FALSE){
                        return '{"err": "'.$bd->error.'"}'; exit;
                    }
                }

            }
        }
    } else {
        return '{"est": "Utilizador B não subscrições"}';
    }
}
?>