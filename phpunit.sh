#!/bin/bash

CONFIGURATION_FILE='phpunit.xml'
PHPUNIT_LOG_FILE=/tmp/phpunit-log.txt

# Guardando inicio da execucao dos testes
start_time=`date +%s`

#echo -e "\nLimpando cache de configuracoes"
#php artisan config:clear

# Executando os testes, jogando saida dos testes em arquivo temporario
# Guardando o retorno do PHPUnit em variavel
echo -e "\nExecutando testes"
./vendor/bin/phpunit \
    --configuration "$CONFIGURATION_FILE" \
    --coverage-text \
    --colors=never \
    --testsuite Auth,Feature \
    $@ | tee $PHPUNIT_LOG_FILE

RETORNO=`echo ${PIPESTATUS[0]}`

# Extraindo do arquivo o % de cobertura de código
COVERAGE=$(egrep '^\s*Lines:\s*\d+.\d+\%' $PHPUNIT_LOG_FILE | egrep -o '[0-9\.]+%' | tr -d '%')

end_time=`date +%s`
# Calculando e exibindo duração dos testes
echo -e "\n\nDuracao dos testes: `expr $end_time - $start_time`s."

if [ "$RETORNO" -ne 0 ]; then
    echo -e "Falha nos testes do PHP Unit!"
fi

echo -e "Cobertura de código: ${COVERAGE}%."

if (( ! $(echo "$COVERAGE > $MINIMUM_CODE_COVERAGE" | bc -l) )); then
    echo -e "Percentual minimo de cobertura de codigo nao atingida: ${MINIMUM_CODE_COVERAGE}%."
    echo -e "Falha no percentual minimo de cobertura de codigo!"
    exit 1
fi

echo -e "\nConfira a pasta 'coverage' para relatorio de cobertura de codigo, abrir index.html com o navegador"

exit $RETORNO
