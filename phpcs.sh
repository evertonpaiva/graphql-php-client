#!/bin/bash

echo -e "\nValidando Padrao de codificacao"
vendor/bin/phpcs
RETORNO=$?

exit $RETORNO
