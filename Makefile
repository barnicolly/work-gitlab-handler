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

notify:
	docker-compose exec php bin/console app:gitlab:notify-about-mr
	docker-compose exec php bin/console notify:gitlab:need-merge

cs:
	docker-compose exec php composer php-cs-fixer