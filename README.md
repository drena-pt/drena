# drena.pt

Rede social de partilha de projetos.
Criada por Guilherme Albuquerque.

## Instalação

### Configuração do nginx

Exemplo de site `/etc/nginx/sites-available/exemplo.com`:

    limit_req_zone $binary_remote_addr zone=main:10m rate=29r/m;
    limit_req_zone $binary_remote_addr zone=fast:10m rate=3r/s;
    server {
        root /home/user/drena/pasta/;
        index index.php index.html;
        server_name exemplo.com;
        client_max_body_size 2G;

        location / {
            try_files $uri $uri/ @extensionless-php;
        }
        location /u/ {
            rewrite ^/u/(.*)$ /perfil.php?uti=$1 last;
        }
        location /m/ {
            rewrite ^/m/(.*)$ /media.php?id=$1 last;
        }
        location @extensionless-php {
            rewrite ^(.*)$ $1.php last;
        }
        location ~ /api/ {
            try_files $uri $uri/ @extensionless-php;
            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php8.1-fpm.sock;
            }
            limit_req zone=main burst=20 nodelay;
            limit_req zone=fast;
            limit_req_status 429;
        }
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        }
    }

### Configuração das variáveis PHP

Alterar as variáveis no php.ini `/etc/php/8.1/fpm/php.ini`:

    upload_max_filesize=2G
    post_max_size=2G
    memory_limit=2G
    max_execution_time=12000
    max_input_time = 12000
    session.gc_maxlifetime=31536000

### Obter o código / bibliotecas necessárias

1. Obtem o código. O nome da `pasta` é alterável:

       git clone https://git.nadaradical.com/guilhae/drena.pt.git pasta
       cd pasta

2. Instalar as dependências do `nodejs` e `composer`:

       npm i | composer i

### Configurar as variáveis da base de dados, URL's e diretórios

1. Copiar o ficheiro das variaveis `pro/fun_var.php.bak` para `pro/fun_var.php`.

       cp pro/fun_var.php.bak pro/fun_var.php

2. Edita o ficheiro `pro/fun_var.php`.

       nano pro/fun_var.php

3. Altera as variáveis.

    ```php
    #Base de dados MySQL
    $bd_hn='hostname';
    $bd_un='username';
    $bd_pw='password';
    $bd_db='database';
    #URL's
    $url_dominio='exemplo.com';
    $url_site   ='https://'.$url_dominio.'/';
    $url_media  ='https://media.'.$url_dominio.'/';
    #Diretórios
    $dir_site   ='/home/user/drena/pasta/';
    $dir_media  ='/home/user/drena/pasta_media/';
    #API
    $api_key    ='secret_random_key';
    #Email
    $ema_host   ='exemplo.com';
    $ema_user   ='mail@exemplo.com';
    $ema_psswd  ='password';
    ```

4. Cria as pastas necessárias para armazenar a média.

        cd /home/user/drena/pasta_media/
        mkdir comp conv fpe img ori som thumb
        chown -R www-data:www-data *

### Configurar a base de dados

1. Cria uma base de dados nova no MySQL igual à que inseriste nas variáveis.

2. Criar as tabelas necessárias.
Abrir a página no navegador. Substituir `https://exemplo.com/` com o URL do site definido em `$url_site` na página de funções.

       https://exemplo.com/pro/tab/INSTALAR.php

### Ativar o GETTEXT para as traduções

Deves instalar o Gettext e verificar a compatibilidade com o PHP
Também é necessário ativar cada língua.
Exemplo (para Alemão da Suiça):

        sudo locale-gen de_CH.UTF-8