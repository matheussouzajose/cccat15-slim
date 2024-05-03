OCKER_COMPOSE_FILE := docker-compose.yaml

DOCKER_COMPOSE := docker compose

DOCKER_EXEC := ${DOCKER_COMPOSE} exec app

.DEFAULT_GOAL := help

help:
	@echo "Uso: make [alvo]"
	@echo ""
	@echo "Alvos disponíveis:"
	@echo "  up             Inicia todos os contêineres definidos no Docker Compose"
	@echo "  down           Para todos os contêineres e remove os volumes"
	@echo "  restart        Reinicia todos os contêineres"
	@echo "  logs           Exibe logs dos contêineres"
	@echo "  exec           Executa um comando em um serviço específico (exemplo: make exec service=comando)"
	@echo "  ps             Lista os contêineres em execução"
	@echo "  help           Exibe esta mensagem de ajuda"

build:
	${DOCKER_COMPOSE} up -d --build;

up:
	${DOCKER_COMPOSE} stop && ${DOCKER_COMPOSE} up -d;

down:
	${DOCKER_COMPOSE} down -v;

stop:
	${DOCKER_COMPOSE} stop;

restart:
	${DOCKER_COMPOSE} restart;

logs:
	${DOCKER_COMPOSE} logs -f;

exec:
	$(DOCKER_COMPOSE) exec $(service) $(command)

ps:
	${DOCKER_COMPOSE} ps;

sh:
	$(DOCKER_EXEC) sh

test-all:
	$(DOCKER_EXEC) composer run tests

analyse:
	$(DOCKER_EXEC) composer run analyse

pint:
	$(DOCKER_EXEC) ./vendor/bin/pint

.PHONY: help up down restart logs exec p
