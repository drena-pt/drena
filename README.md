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

1. Obtem o código:

       git clone https://git.nadaradical.com/guilhae/drena.pt.git pasta

2. Instalar as dependências do `nodejs`:

       npm i

   Instalar as dependências do `composer`:

       composer i

### Configurar URL's e diretórios

1. Edita o ficheiro `pro/fun.php`.

       nano pro/fun.php

2. Altera as variáveis.

    ```php
    # URL's e diretórios
    $url_site	= 'https://exemplo.com/';
    $url_media	= 'https://media.exemplo.com/';

    $dir_site	= '/home/user/drena/pasta/';
    $dir_media	= '/home/user/drena/pasta_media/';
    ```

### Configurar a base de dados

1. Cria uma base de dados nova no MySQL.

2. Copiar o ficheiro `pro/ligarbd.php.bak` para `pro/ligarbd.php`

       cp pro/ligarbd.php.bak pro/ligarbd.php

3. Configurar `pro/ligarbd.php` com os dados de conexão para a nova base de dados criada MySQL.

4. Criar as tabelas necessárias.
Abrir a página no navegador. Substituir `https://exemplo.com/` com o URL do site definido em `$url_site` na página de funções.

       https://exemplo.com/pro/tab/INSTALAR.php