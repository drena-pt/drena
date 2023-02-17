<?php /*
require 'fun.php'; #Funções

# Procura por uma secção e/ou define o projeto.
if ($_GET['sec']){
    $sec = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro_sec WHERE id='".base64_decode($_GET["sec"])."'"));   # Informações da secção
    $pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".$sec["pro"]."'"));                       # Informações do projeto baseado na secção
} else if ($_GET['pro']){
    $pro = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM pro WHERE id='".base64_decode($_GET["pro"])."'"));       # Informações do projeto
} else {
    echo "Erro: Nenhum projeto encontrado";
    exit;
}

# Se o projeto existir e o utilizador for dono do projeto
if ($pro AND $pro['uti']==$uti['id']){

    if ($_GET['ac']=='criar'){ # Criar Secção
        $sql = "INSERT INTO pro_sec (pro) VALUES ('".$pro["id"]."')";
        if (!mysqli_query($bd, $sql)){
            echo "Erro: " . $sql . "<br>" . $bd->error;
        }
    } else if ($sec){ # Se a secção existir

        if ($_GET['ac']=='eliminar'){ # Se a ação for eliminar
            if ($bd->query("DELETE FROM pro_sec WHERE id='".$sec['id']."'") === FALSE) {
                echo "Erro: ".$bd->error;
                exit;
            }
        } else if ($_GET['ac']=='visibilidade'){ # Se a ação for alterar a visibilidade
            if ($sec['vis']==0){
                $bd->query("UPDATE pro_sec SET vis=1 WHERE id='".$sec['id']."'");
                echo "true";
            } else {
                $bd->query("UPDATE pro_sec SET vis=0 WHERE id='".$sec['id']."'");
                echo "‎false";
            }
            exit;
        } else if ($_GET['ac']=='guardar'){ # Se a ação for guardar o texto
            $sec_texto = addslashes($_POST['texto']);

            if ($bd->query("UPDATE pro_sec SET tex='".$sec_texto."' WHERE id='".$sec['id']."'") === FALSE) {
                echo "Error:".$bd->error;
            }
            exit;
        }

    } else {
        echo "Erro: Secção inválida!";
        exit;
    }

    organizarSec:
    $array_sec = array();
    $pesquisa = "SELECT * FROM pro_sec WHERE pro=".$pro['id']." ORDER BY ord ASC";
    if ($resultado = $bd->query($pesquisa)) {
        $num_sec = $resultado->num_rows;
        while ($campo = $resultado->fetch_assoc()) {
            array_push($array_sec, $campo['id']);
        }
    }
    $resultado->free();
    
    if ($_GET['ac']=='moverCima' OR $_GET['ac']=='moverBaixo'){ # Se a ação for mover para cima ou baixo.
        $numeroAtual = array_search($sec['id'], $array_sec);
        if ($_GET['ac']=='moverBaixo'){
            $numeroSeguinte = $numeroAtual + 1;
        } else if ($_GET['ac']=='moverCima'){
            $numeroSeguinte = $numeroAtual - 1;
        }
        if ($numeroSeguinte>=0 AND $numeroSeguinte<=count($array_sec)){
            $array_sec[$numeroAtual] = $array_sec[$numeroSeguinte];
            $array_sec[$numeroSeguinte] = $sec['id'];
        }
    }
    
    foreach ($array_sec as $key => $value) {
        $bd->query("UPDATE pro_sec SET ord='".$key."' WHERE id='".$value."'");
    }

    if ($_GET['ac']=='moverCima' OR $_GET['ac']=='moverBaixo'){
        header("Location: /../projeto.php?id=".base64_encode($pro['id'])."#sec_".$numeroSeguinte);
    }

} else {
    echo "Erro: Projeto inválido!";
    exit;
}

exit;
*/ ?>