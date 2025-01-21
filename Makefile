PROJECT_NAME := laravel_ghipy
CURRENT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
PHP_CONTAINER := $(PROJECT_NAME)-php
DOCKER_EXEC := docker exec -t --user=$(id -u):$(id -g) $(PHP_CONTAINER)

.PHONY: help build

help: ## Print this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

composer-install: ## Install composer dependencies
	@docker run --rm $(INTERACTIVE) --volume $(CURRENT_DIR):/app --user $(id -u):$(id -g) \
		composer:2.8.4 install \
			--ignore-platform-reqs \
			--no-ansi

start: composer-install ## Start the containers
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@docker compose up --build -d
	@make clean-cache

fresh-start: start ## Start the container for first time
	make prepare-db
	$(DOCKER_EXEC) php artisan key:generate
	$(DOCKER_EXEC) php artisan passport:install

stop: ## Stop the containers
	@docker compose stop

destroy: ## Delete the containers, networks and volumes
	docker compose down

build: ## Rebuild the containers from scratch
	docker compose build --pull --force-rm --no-cache
	make fresh-start


static-analysis: ## Runs static code analysis to check for errors, architecture violations, and code quality issues.
	$(DOCKER_EXEC) php ./vendor/psalm/phar/psalm.phar --config ./tools/psalm.xml

mess-detector: ## Checks code for dirty code
	$(DOCKER_EXEC) php ./vendor/bin/phpmd src,tests,tools text ./tools/phpmd.xml

lint: ## Runs the linter to check for code style violations
	$(DOCKER_EXEC) php ./vendor/bin/ecs check --config ./tools/ecs.php

test-architecture:
	$(DOCKER_EXEC) php -d memory_limit=4G ./vendor/bin/phpstan analyse -c ./tools/phpstan.neon

test:
	$(DOCKER_EXEC) php artisan config:cache --env=testing
	@mkdir -p build/test_results/phpunit
	@$(DOCKER_EXEC) php ./vendor/bin/phpunit --testdox --exclude-group='disabled' -c ./tools/phpunit.xml
	@$(DOCKER_EXEC) php ./vendor/bin/behat --format=pretty -v --config ./tools/behat.yml
	@$(DOCKER_EXEC) php artisan config:cache --env=local && make clean-cache

prepare-db:
	@$(DOCKER_EXEC) php -d APP_ENV=testing artisan db:wipe --force
	@$(DOCKER_EXEC) php artisan migrate
	@$(DOCKER_EXEC) php artisan doctrine:migrations:migrate --no-interaction
	make seed-db

seed-db:
	@$(DOCKER_EXEC) php artisan db:seed

ping-mysql: ## Ping the mysql service
	@docker exec laravel_ghipy-db mysqladmin --user=laraveluser --password=secret --host "127.0.0.1" ping --silent

clean-cache: ## Clean app cache
	@$(DOCKER_EXEC) php artisan cache:clear
	@$(DOCKER_EXEC) php artisan config:clear
	@$(DOCKER_EXEC) php artisan view:clear

shell: ## Enter shell php container
	docker exec -it $(PHP_CONTAINER) bash
