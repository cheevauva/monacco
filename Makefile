COMPOSE_DEV=./docker/dev/docker-compose.yaml
COMPOSE_NAME=monacco
COMPOSE_ENV=./.docker.env

up:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) up --build -d
down:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) down
php:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) exec php bash
logs:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) logs -f
ps:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) ps
composer_update:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) exec php composer update
composer_install:;docker compose -p $(COMPOSE_NAME) -f $(COMPOSE_DEV) --env-file $(COMPOSE_ENV) exec php composer install --prefer-dist --no-dev --no-scripts --no-plugins --no-interaction --no-progress