#!/bin/bash

set -e

echo -e "\nConstruindo a imagem localmente"
docker-compose build
