FROM hub.dds.ufvjm.edu.br/desenvolvimento/alpine-php7:latest

# Habilitando xdebug
RUN sed -i "s|;*zend_extension=xdebug.so|zend_extension=xdebug.so|i" /etc/php7/conf.d/xdebug.ini

# copiando o código do repositório para o working_dir (/app) do container
ADD . .

# atualizando pacotes
RUN apk update

#  instalando git
RUN apk add --no-cache git bash

# atualizando o composer
RUN composer self-update

# instalando dependências do composer
RUN composer install
