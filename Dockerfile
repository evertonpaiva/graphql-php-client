FROM hub.dds.ufvjm.edu.br/desenvolvimento/apache-debian-php:7.3.33

# Habilitando xdebug
ENV XDEBUG_MODE coverage
RUN docker-php-ext-enable xdebug

# copiando o código do repositório para o working_dir (/app) do container
ADD . .

# atualizando pacotes
RUN apt-get update && apt-get upgrade -y

# instalando pacotes
RUN apt-get install bc -y

# atualizando o composer
RUN composer self-update

# instalando dependências do composer
RUN composer update && composer install
