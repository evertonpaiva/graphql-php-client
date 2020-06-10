#!/bin/bash

set -e

echo -e "\nAtualizando imagem base"
docker pull hub.dds.ufvjm.edu.br/desenvolvimento/alpine-php7:latest

echo -e "\nConstruindo a imagem localmente"
docker-compose build
