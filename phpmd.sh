#!/bin/bash

echo -e "\nVerificador de erros/sujeira PHP Mess Detector"
vendor/bin/phpmd src/ text phpmd-ruleset.xml --exclude vendor --exclude src/Session
RETORNO=$?

exit $RETORNO
