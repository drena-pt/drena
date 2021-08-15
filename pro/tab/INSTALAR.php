<?php
$funcoes['requerSessao']=0;
require '../fun.php'; #Funções

#Criar todas as tabelas na base de dados
require('uti.php');         #Utilizador 
require('uti_fot.php');     #Utilizador     - Fotos    
require('uti_mai.php');     #Utilizador     - 
require('ami.php');         #Amigos
require('med.php');         #Média
require('med_alb.php');     #Média          - Albuns
require('med_com.php');     #Média          - Comentários
require('med_gos.php');     #Média          - Gostos
require('med_thu.php');     #Média          - Thumbnails
require('med_mod.php');     #Média          - Moderação
require('pro.php');         #Projetos
require('pro_sec.php');     #Projetos       - Secções
require('not_sub.php');     #Notificações   - Subscrições
?>