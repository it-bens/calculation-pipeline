.PHONY: help docker-build composer-install composer-update composer-require composer-require-dev composer-remove composer-clean cache-clear command style-check style-fix code-check phpunit-tests
.DEFAULT_GOAL := help
.USER_ID := $(shell id -u)

help:
    @grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

docker-build:
	docker-compose down
	docker-compose build --pull
	docker-compose up -d

composer-install:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app composer install

composer-update:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app composer update

composer-require:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app composer req $(package)

composer-require-dev:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app composer req --dev $(package)

composer-remove:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app composer rem $(package)

composer-clean:
	rm -Rf ./vendor ./composer.lock

style-check:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app vendor/bin/phpcs --standard=PSR1,PSR12 --exclude=Generic.Files.LineLength $(folders)

style-fix:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app vendor/bin/phpcbf --standard=PSR1,PSR12 --exclude=Generic.Files.LineLength $(folders)

code-check-phpstan:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) phpstan analyse -c phpstan.neon

code-check-psalm:
	docker-compose run --rm -T app vendor/bin/psalm --config=psalm.xml

phpunit-tests:
	docker-compose run --rm -T --volume /etc/passwd:/etc/passwd:ro --user $(.USER_ID) app vendor/bin/phpunit -c phpunit.xml.dist


github-composer-install:
	composer install --no-interaction --no-progress --no-suggest --optimize-autoloader

github-tests-phpunit: github-composer-install
	XDEBUG_MODE=coverage ./vendor/bin/phpunit -c ./phpunit.xml.dist

github-tests-phpstan: github-composer-install
	./vendor/bin/phpstan analyse -c ./phpstan.neon

github-tests-codesniffer: github-composer-install
	./vendor/bin/phpcs --standard=PSR1,PSR12 --exclude=Generic.Files.LineLength src tests