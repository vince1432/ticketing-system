setup:
	@make db-set
	@make link
	@make passport-install
docker-setup:
	@make build
	@make up
	@make composer-update
db-reset:
	@make migrate-fresh
	@make passport-install
db-set:
	docker exec -it ticketing-backend bash -c "php artisan migrate --seed"
migrate-fresh:
	docker exec -it ticketing-backend bash -c "php artisan migrate:fresh --seed"
migrate:
	docker exec -it ticketing-backend bash -c "php artisan migrate"
seed:
	docker exec -it ticketing-backend bash -c "php artisan db:seed"
passport-install:
	docker exec -it ticketing-backend bash -c "php artisan passport:install"
link:
	docker exec -it ticketing-backend bash -c "php artisan storage:link --force"
clear-cache:
	docker exec -it ticketing-backend bash -c "php artisan cache:clear"
composer-update:
	docker exec -it ticketing-backend bash -c "composer update"
build:
	docker-compose build
build-no-cache:
	docker-compose build --no-cache --force-rm
stop:
	docker-compose stop
down:
	docker-compose down -v
up:
	docker-compose up -d
docker:
	docker exec -it ticketing-backend bash
