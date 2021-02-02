#!make

ifndef DOCKER_MAIN_PATH
	DOCKER_MAIN_PATH=$(CURDIR)
endif

COMPOSER_CMD=composer
GO_TO_DOCKER_DIRECTORY=cd $(DOCKER_MAIN_PATH) &&
NODE_BASH=$(GO_TO_DOCKER_DIRECTORY) docker-compose exec node
DOCKER_BASH=$(GO_TO_DOCKER_DIRECTORY) docker-compose exec php-fpm
MIGRATE_DATABASE=php artisan migrate --no-interaction
DROP_DATABASE=php artisan db:wipe

BUILD_PRINT = @echo "\033[0;32m [\#] \033[0m \033[0;33m \033[0m"

.DEFAULT_GOAL := help

help:                                                                           ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

#SILENT=> /dev/null 2>&1
SILENT=

#================================================
# MAIN
#================================================
start: start-docker composer-install ## run application
	${BUILD_PRINT} DONE

stop: ## Stop docker services
	$(GO_TO_DOCKER_DIRECTORY) docker-compose stop

restart: rm start ##remove containers and run

rm: ## Remove all docker services
	${BUILD_PRINT} REMOVING ALL SERVICES
	$(GO_TO_DOCKER_DIRECTORY) docker-compose down -v $(SILENT)
start-docker: ## start docker containers
	$(GO_TO_DOCKER_DIRECTORY) docker-compose up -d $(SILENT)

install: composer-install ## install application: composer

apply-code-updates: composer-install migrate-database front-install front-build


warmup-project: start copy-env install migrate-database front-install front-build ## install project from scratch

#================================================
# SETTINGS
#================================================
bash: ## run docker bash
	$(GO_TO_DOCKER_DIRECTORY) docker-compose exec php-fpm bash

#================================================
# APPLICATION MANAGEMENT
#================================================

phpunit: ## run unit tests
	${DOCKER_BASH} bin/phpunit

coverage: ## run phpunit with coverage generation
	${DOCKER_BASH} bin/phpunit --coverage-html=coverage

composer-install:  ## run composer install
	$(DOCKER_BASH) $(COMPOSER_CMD) install --no-interaction --optimize-autoloader


clear-cache:  ##clear cache
	$(DOCKER_BASH) php artisan cache:clear


copy-env: ## copy .env.dist to .env
	$(DOCKER_BASH) cp .env.example .env
	$(DOCKER_BASH) php artisan key:generate



#================================================
# DATABASE
#================================================


drop-database: ## CREATE database
	$(DOCKER_BASH) $(DROP_DATABASE)

migrate-database: ## MIGRATE database
	$(DOCKER_BASH) $(MIGRATE_DATABASE)


recreate-db: drop-database migrate-database ## removes and creates new, empty db


#================================================
# FRONTEND
#================================================

front-install:
	$(NODE_BASH) yarn install

front-build:
	$(NODE_BASH) yarn run dev

front-watch:
	$(NODE_BASH) yarn run watch
