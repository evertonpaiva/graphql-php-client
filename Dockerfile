FROM hub.dds.ufvjm.edu.br/desenvolvimento/alpine-php7:latest

# copiando o código do repositório para o working_dir (/app) do container
ADD . .

# atualizando pacotes
RUN apk update

#  instalando git
RUN apk add --no-cache git bash

# dependencias dp PHP
RUN apk add --no-cache php7-simplexml

# atualizando o composer
RUN composer self-update

# instalando primeiro o pacote prestissimo para permitir download paralelo de dependencias
RUN composer global require hirak/prestissimo:0.3.9

# instalando dependências do composer
RUN composer install
