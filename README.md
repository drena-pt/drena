# drena.pt

Rede social de partilha de projetos.
Criada por Guilherme Albuquerque.

## Instalação

### Configuração do nginx

Exemplo de site `/etc/nginx/sites-available/exemplo.com`:

    server {
        root /home/user/drena/pasta/;
        index index.php index.html;
        server_name exemplo.com;

        location / {
            try_files $uri $uri/ @extensionless-php;
        }
        location /fpe {
            rewrite ^/fpe/(.*)$ /fpe.php?id=$1 last;
        }
        location @extensionless-php {
            rewrite ^(.*)$ $1.php last;
        }
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php8.0-fpm.sock;
        }
    }

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
    $url_site   ='https://exemplo.com/';
    $url_media  ='https://media.exemplo.com/';
    #Diretórios
    $dir_site   ='/home/user/drena/pasta/';
    $dir_media  ='/home/user/drena/pasta_media/';
    ```

4. Cria as pastas necessárias para armazenar a média.

        cd /home/user/drena/pasta_media/
        mkdir comp | mkdir conv | mkdir img | mkdir ori | mkdir som | mkdir thumb
        sudo chown -R www-data:www-data *

### Configurar a base de dados

1. Cria uma base de dados nova no MySQL igual à que inseriste nas variáveis.

2. Criar as tabelas necessárias.
Abrir a página no navegador. Substituir `https://exemplo.com/` com o URL do site definido em `$url_site` na página de funções.

       https://exemplo.com/pro/tab/INSTALAR.php