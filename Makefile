COMPOSE_DEV=./docker/dev/docker-compose.yaml
COMPOSE_NAME=monacco
COMPOSE_ENV=./.docker.env

include .env.Makefile

up:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) up --build -d
down:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) down
php:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) exec php bash
logs:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) logs -f
ps:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) ps
composer_update:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) exec php composer update
composer_install:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) exec php composer install --prefer-dist --no-dev --no-scripts --no-plugins --no-interaction --no-progress
prod_build:;docker build -f ./docker/prod/Dockerfile -t $(REGISTRY_REPOSITORY):$(REGISTRY_TAG) .
prod_remove:;docker image rm  $(REGISTRY_REPOSITORY):$(REGISTRY_TAG) -f
prod_bash:;docker run -it $(REGISTRY_REPOSITORY):$(REGISTRY_TAG) 
prod_tag:;docker image tag $(REGISTRY_REPOSITORY):$(REGISTRY_TAG) $(REGISTRY_HOST)/cheevauva/monacco:latest
prod_push:;docker image push $(REGISTRY_HOST)/cheevauva/monacco:latest