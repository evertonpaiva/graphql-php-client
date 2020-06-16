# Uso:
# make        # cria a imagem docker, atualiza as dependencias do composer, executa a imagem
# make tests  # executa o Phpunit, PHP Code Beauty Fixer e PHP Mess Detector
# make clean  # remove a imagem docker

all: build update run

tests: test lint-fix lint-md security-check

clean:
	./stop.sh

build:
	./build.sh

update:
	./update.sh

run:
	./run.sh

test:
	docker exec -it graphql-client bash ./phpunit.sh

lint-check:
	docker exec -it graphql-client bash ./phpcs.sh

lint-fix:
	docker exec -it graphql-client bash ./phpcbf.sh

lint-md:
	docker exec -it graphql-client bash phpmd.sh

security-check:
	docker exec -it graphql-client bash ./security-check.sh
