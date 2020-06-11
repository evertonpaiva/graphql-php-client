#!/bin/bash

echo -e "\nVulnerabilidades de seguranca nas bibliotecas"
vendor/bin/security-checker security:check
RETORNO=$?

exit $RETORNO
