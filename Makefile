create: up ps

up:
	docker-compose up -d
ps:
	docker-compose ps
rebuild:
	docker-compose down
	docker-compose build --no-cache
	make up
down:
	docker-compose down
# основная команда старта среды docker-compose
go: up ps php
# вход в php контейнер
php:
	docker-compose exec php sh

cs:
	docker-compose exec php composer php-cs-fixer