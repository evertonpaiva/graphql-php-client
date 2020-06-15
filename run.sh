#!/bin/bash

set -e

COMPOSER_IMG=graphql-client:dev

echo -e "\nParando a stack"
docker-compose down

echo -e "\nAtualizando composer.lock"
docker run --rm --interactive --tty \
            --volume "${(PWD)}":/app \
            $COMPOSER_IMG composer update

echo -e "\nInstalando dependências localmente"
docker run --rm --interactive --tty \
            --volume "${(PWD)}":/app \
            $COMPOSER_IMG composer install

echo -e "\nCorrigindo permissões na pasta da aplicação"
sudo chown "${USER}":www-data -R .

echo -e "\nIniciando o container"
docker-compose up -d
