.DEFAULT_GOAL := help
.PHONY: help run stop

help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n\033[36m\033[0m"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST) && echo ''

APP_NAME ?= "test_commissions"
COMPOSE_FILES=-f docker-compose.yml -f docker-compose.override.yml

start: ## Starts Docker containers
	@test -s .env || cp .env.dist .env
	@test -s docker-compose.override.yml || cp docker-compose.override.dist.yml docker-compose.override.yml
	@bash scripts/start.sh $(APP_NAME) '$(COMPOSE_FILES)'

stop: ## Stops Docker containers
	@bash scripts/stop.sh $(APP_NAME) '$(COMPOSE_FILES)'

sh: ## SHs into the Docker container
	docker-compose -p $(APP_NAME) $(COMPOSE_FILES) exec php-fpm /bin/ash

dump-config: ## Dumps docker-compose config
	docker-compose config

ps: ## Show containers list
	docker-compose -p $(APP_NAME) $(COMPOSE_FILES) ps

up: ## Up containers without build
	docker-compose -p $(APP_NAME) $(COMPOSE_FILES) up -d

down: ## Down containers
	docker-compose -p $(APP_NAME) $(COMPOSE_FILES) down
%:
@:
